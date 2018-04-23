<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use App\Libs\Utilities;
use App\Models\DeliveryNoteHeader;
use App\Models\DeliveryOrderHeader;
use App\Models\Document;
use App\Models\Item;
use App\Models\ItemReceiptDetail;
use App\Models\ItemReceiptHeader;
use App\Models\ItemStock;
use App\Models\NumberingSystem;
use App\Models\PurchaseOrderDetail;
use App\Models\PurchaseOrderHeader;
use App\Models\StockCard;
use App\Models\Warehouse;
use App\Transformer\Inventory\ItemReceiptTransformer;
use App\Transformer\Inventory\PurchaseOrderTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use PDF;

class ItemReceiptController extends Controller
{
    //
    public function index(){
        return View('admin.inventory.item_receipts.index');
    }

    public function show(ItemReceiptHeader $item_receipt){
        $header = $item_receipt;

        return View('admin.inventory.item_receipts.show', compact('header'));
    }

    public function createPo(){
        return View('admin.inventory.item_receipts.po_list');
    }

    public function getPurchaseOrder(){
        try{
            $purchaseOrders = PurchaseOrderHeader::where('status_id', 3)->dateDescending()->get();
            return DataTables::of($purchaseOrders)
                ->setTransformer(new PurchaseOrderTransformer())
                ->addIndexColumn()
                ->make(true);
        }
        catch(\Exception $ex){
            error_log($ex);
        }
    }

    public function create(){
        $purchaseOrder = null;
        if(!empty(request()->po)){
            $purchaseOrder = PurchaseOrderHeader::find(request()->po);
        }

        $deliveries = DeliveryOrderHeader::all();
        $sysNo = NumberingSystem::where('doc_id', '2')->first();
        $document = Document::where('id', '2')->first();
        $autoNumber = Utilities::GenerateNumber($document->code, $sysNo->next_no);
        $warehouse = Warehouse::where('id', '!=', 0)->get();

        $data = [
            'purchaseOrder' => $purchaseOrder,
            'warehouse'     => $warehouse,
            'deliveries'    => $deliveries,
            'autoNumber'    => $autoNumber
        ];

        return View('admin.inventory.item_receipts.create')->with($data);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'code'          => 'max:40',
            'date'          => 'required',
            'po_code'       => 'required',
            'warehouse'     => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        //Generate AutoNumber
        if(Input::get('auto_number')){
            $sysNo = NumberingSystem::where('doc_id', '2')->first();
            $document = Document::where('id', '2')->first();
            $itemReceiptNumber = Utilities::GenerateNumber($document->code, $sysNo->next_no);

            // Check existing number
            $check = ItemReceiptHeader::where('code', $itemReceiptNumber)->first();
            if($check != null){
                return redirect()->back()->withErrors('Nomor Goods Receipt sudah terdaftar!', 'default')->withInput($request->all());
            }

            $sysNo->next_no++;
            $sysNo->save();
        }
        else{
            if(empty(Input::get('code'))){
                return redirect()->back()->withErrors('Nomor Goods Receipt Wajib Diisi!', 'default')->withInput($request->all());
            }

            $itemReceiptNumber = Input::get('code');

            // Check existing number
            $check = ItemReceiptHeader::where('code', $itemReceiptNumber)->first();
            if($check != null){
                return redirect()->back()->withErrors('Nomor Goods Receipt sudah terdaftar!', 'default')->withInput($request->all());
            }
        }

        // Validate details
        $items = Input::get('item_value');
        $qtys = Input::get('qty');
        $valid = true;
        $i = 0;
        $purchaseOrderCode = Input::get('po_code');
        $qty = Input::get('qty');

        foreach($items as $item){
            if(empty($item)) $valid = false;
            if(empty($qtys[$i]) || $qtys[$i] == '0') $valid = false;

            $i++;
        }

        if(!$valid){
            return redirect()->back()->withErrors('Kuantitas inventory wajib diisi!', 'default')->withInput($request->all());
        }

        // Check duplicate inventory
        $valid = Utilities::arrayIsUnique($items);
        if(!$valid){
            return redirect()->back()->withErrors('Detail inventory tidak boleh kembar!', 'default')->withInput($request->all());
        }

        $validPo = true;
        $validQtyPo = true;
        $i = 0;
        $purchaseOrder = PurchaseOrderHeader::where('code', $purchaseOrderCode)->first();


        foreach ($items as $item){
            //Check Data
            //Data Check with PO
            $detail = $purchaseOrder->purchase_order_details->where('item_id', $item)->first();

            if($detail == null || $detail->count() == 0){
                $validPo = false;
            }
            else{
                if($qty[$i] > $detail->quantity){
                    $validQtyPo = false;
                }
            }
            $i++;
        }

        if(!$validPo){
            return redirect()->back()->withErrors('Inventory tidak ada dalam PO!', 'default')->withInput($request->all());
        }
        if(!$validQtyPo){
            return redirect()->back()->withErrors('Kuantitas inventory melebihi kuantitas PO!', 'default')->withInput($request->all());
        }

        $user = \Auth::user();
        $now = Carbon::now('Asia/Jakarta');
        $date = Carbon::parse(Input::get('date'));

        $itemReceiptHeader = ItemReceiptHeader::create([
            'code'                  => $itemReceiptNumber,
            'date'                  => $date->toDateString(),
            'purchase_order_id'     => $purchaseOrder->id,
            'warehouse_id'          => Input::get('warehouse'),
            'delivery_order_vendor' => Input::get('delivery_order'),
            'status_id'             => 1,
            'created_by'            => $user->id,
            'updated_by'            => $user->id,
            'created_at'            => $now->toDateTimeString(),
        ]);

        // Create Item Receipt Detail
        $remark = Input::get('remark');
        $idx = 0;
        foreach(Input::get('item_value') as $item){
            if(!empty($item)){
                $qtyInt = (int) $qty[$idx];

                $itemReceiptDetail = ItemReceiptDetail::create([
                    'header_id'         => $itemReceiptHeader->id,
                    'item_id'           => $item,
                    'remark'            => $remark[$idx],
                    'quantity'          => $qtyInt
                ]);

                if(!empty($remark[$idx])) $itemReceiptDetail->remark = $remark[$idx];
                $itemReceiptDetail->save();

                // Update Stok
                $stockResult = 0;
                $itemStockData = ItemStock::where('item_id', $item)
                    ->where('warehouse_id',Input::get('warehouse'))
                    ->first();

                if(!empty($itemStockData)){
                    $itemStockData->stock = $itemStockData->stock + $qtyInt;
                    $itemStockData->save();

                    $stockResult = $itemStockData->stock;
                }
                else{
                    $newStock = new ItemStock();
                    $newStock->warehouse_id = $request->input('warehouse');
                    $newStock->item_id = $item;
                    $newStock->stock = $qtyInt;
                    $newStock->created_by = $user->id;
                    $newStock->created_at = $now->toDateTimeString();
                    $newStock->updated_by = $user->id;
                    $newStock->save();

                    $stockResult = $newStock->stock;
                }

                $itemData = Item::find($item);
                $itemData->stock += $qtyInt;
                $itemData->save();

                //Stock Card
                StockCard::create([
                    'item_id'       => $item,
                    'change'        => $qtyInt,
                    'stock'         => $stockResult,
                    'warehouse_id'  => Input::get('warehouse'),
                    'created_by'    => $user->id,
                    'created_at'    => $now,
                    'flag'          => '-',
                    'description'   => 'Goods Receipt ' . $itemReceiptHeader->code
                ]);

                //Update PO
                $detail = $purchaseOrder->purchase_order_details->where('item_id', $item)->first();
                $detail->received_quantity = $detail->received_quantity + $qtyInt;
                $detail->save();

                //Update MR
                $mrDetail = $purchaseOrder->purchase_request_header->material_request_header->material_request_details->where('item_id', $item)->first();
                if(!empty($mrDetail)){
                    $mrDetail->quantity_received = $mrDetail->quantity_received + $qtyInt;
                    $mrDetail->save();
                }
            }
            $idx++;
        }

        //Check PO
//        $idx = 0;
//        foreach(Input::get('item_value') as $item){
//            if(!empty($item)){
//                //Update PO
//                $poCount = 1;
//
//                foreach($purchaseOrder->purchase_order_details as $detail){
//                    if($detail->quantity != $detail->received_quantity){
//                        $poCount = 0;
//                    }
//                }
//
//                if($poCount == 1){
//                    $purchaseOrder->status_id = 4;
//                    $purchaseOrder->closing_date = $now->toDateString();
//                    $purchaseOrder->save();
//                }
//            }
//            $idx++;
//        }

        Session::flash('message', 'Berhasil membuat Item Receipt!');

        return redirect()->route('admin.item_receipts.show', ['item_receipts' => $itemReceiptHeader]);
    }

    public function edit($id){
        $header = ItemReceiptHeader::find($id);

        return View('admin.inventory.item_receipts.edit', compact('header'));
    }

    public function update(Request $request, $id){
        $validator = Validator::make($request->all(),[
            'date'          => 'required',
            'no_sj_spb'     => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = \Auth::user();
        $now = Carbon::now('Asia/Jakarta');
        $formatedDate = Carbon::createFromFormat('d M Y', Input::get('date'), 'Asia/Jakarta');

        $itemReceiptHeader = ItemReceiptHeader::find($id);

        if(!empty(Input::get('delivery_order'))){
            $itemReceiptHeader->delivery_order_id = Input::get('delivery_order');
        }

        $itemReceiptHeader->date = $formatedDate->toDateTimeString();
        $itemReceiptHeader->delivered_from = Input::get('delivered_from');
        $itemReceiptHeader->angkutan = Input::get('angkutan');
        $itemReceiptHeader->updated_by = $user->id;
        $itemReceiptHeader->updated_at = $now->toDateTimeString();
        $itemReceiptHeader->save();

        Session::flash('message', 'Berhasil mengubah Item Receipt!');

        return redirect()->route('admin.item_receipts.edit', ['item_receipts' => $itemReceiptHeader->id]);
    }

    public function delete(){

    }

    public function printDocument($id){
        $itemReceipt = ItemReceiptHeader::find($id);
        $itemReceiptDetails = ItemReceiptDetail::where('header_id', $itemReceipt->id)->get();

        $itemTotal = 0;
        foreach($itemReceiptDetails as $detail){
            $itemTotal += $detail->quantity;
        }

        return view('documents.item_receipts.item_receipts', compact('itemReceipt', 'itemReceiptDetails', 'itemTotal'));
    }

    public function report(){
        return View('admin.inventory.item_receipts.report');
    }

    public function downloadReport(Request $request) {
        //Get Data First
        $tempStart = strtotime(Input::get('start_date'));
        $start = date('Y-m-d', $tempStart);
        $tempEnd = strtotime(Input::get('end_date'));
        $end = date('Y-m-d', $tempEnd);

        //Check date
        if($start > $end){
            return redirect()->back()->withErrors('Start Date Tidak boleh lebih besar dari Finish Date!', 'default')->withInput($request->all());
        }

        $data = ItemReceiptHeader::whereBetween('date', array($start, $end))->get();

        //Check Data
        if($data == null || $data->count() == 0){
            return redirect()->back()->withErrors('Data Tidak Ditemukan!', 'default')->withInput($request->all());
        }

        $pdf = PDF::loadView('documents.item_receipts.item_receipts_pdf', ['data' => $data, 'start_date' => Input::get('start_date'), 'finish_date' => Input::get('end_date')])
            ->setPaper('a4', 'landscape');
        $now = Carbon::now('Asia/Jakarta');
        $filename = 'ITEM_RECEIPT_REPORT_' . $now->toDateTimeString();
        $pdf->setOptions(["isPhpEnabled"=>true]);

        return $pdf->download($filename.'.pdf');
    }

    public function download($id){
        $itemReceipt = ItemReceiptHeader::find($id);
        $itemReceiptDetails = ItemReceiptDetail::where('header_id', $itemReceipt->id)->get();

        $pdf = PDF::loadView('documents.item_receipts.item_receipts_doc', ['itemReceipt' => $itemReceipt, 'itemReceiptDetails' => $itemReceiptDetails])->setPaper('A4');
        $now = Carbon::now('Asia/Jakarta');
        $filename = $itemReceipt->code. '_' . $now->toDateTimeString();

        return $pdf->download($filename.'.pdf');
    }

    public function getIndex(){
        $itemReceipts = ItemReceiptHeader::all();
        return DataTables::of($itemReceipts)
            ->setTransformer(new ItemReceiptTransformer())
            ->addIndexColumn()
            ->make(true);
    }
}
