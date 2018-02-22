<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use App\Libs\Utilities;
use App\Models\DeliveryNoteHeader;
use App\Models\DeliveryOrderHeader;
use App\Models\ItemReceiptDetail;
use App\Models\ItemReceiptHeader;
use App\Models\NumberingSystem;
use App\Transformer\Inventory\ItemReceiptTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class ItemReceiptController extends Controller
{
    //
    public function index(){
        return View('admin.inventory.item_receipts.index');
    }

    public function show($id){
        $header = ItemReceiptHeader::find($id);

        return View('admin.inventory.item_receipts.show', compact('header'));
    }

    public function create(){
        $deliveries = DeliveryOrderHeader::all();

        return View('admin.inventory.item_receipts.create', compact('deliveries'));
    }

    public function store(Request $request){
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

        //Generate AutoNumber
        if(Input::get('auto_number')){
            $sysNo = NumberingSystem::where('doc_id', '2')->first();
            $docketNumber = Utilities::GenerateNumber('BTB', $sysNo->next_no);
            $sysNo->next_no++;
            $sysNo->save();
        }
        else{
            $docketNumber = Input::get('code');
        }

        //Check Code
        $check = ItemReceiptHeader::where('code', $docketNumber)->first();
        if($check != null){
            return redirect()->back()->withErrors('Nomor Issued Docket Sudah terdaftar!', 'default')->withInput($request->all());
        }

        $user = \Auth::user();
        $now = Carbon::now('Asia/Jakarta');

        $itemReceiptHeader = ItemReceiptHeader::create([
            'code'              => $docketNumber,
            'no_sj_spb'         => Input::get('no_sj_spb'),
            'date'              => $now->toDateTimeString(),
            'delivery_order_id' => Input::get('delivery_order'),
            'delivered_from'    => Input::get('delivered_from'),
            'angkutan'          => Input::get('angkutan'),
            'status_id'         => 1,
            'created_by'        => $user->id,
            'updated_by'        => $user->id,
            'created_at'        => $now->toDateTimeString(),
        ]);

        // Create Item Receipt Detail
        $qty = Input::get('qty');
        $remark = Input::get('remark');
        $purchaseOrderId = Input::get('po');
        $idx = 0;
        foreach(Input::get('item') as $item){
            if(!empty($item)){
                $itemReceiptDetail = ItemReceiptDetail::create([
                    'header_id'         => $itemReceiptHeader->id,
                    'item_id'           => $item,
                    'purchase_order_id' => $purchaseOrderId[$idx],
                    'remark'            => $remark[$idx],
                    'quantity'          => $qty[$idx]
                ]);

                if(!empty($remark[$idx])) $itemReceiptDetail->remark = $remark[$idx];
                $itemReceiptDetail->save();
            }
            $idx++;
        }

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

        $itemReceiptHeader->no_sj_spb = Input::get('no_sj_spb');
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

    public function getIndex(){
        $itemReceipts = ItemReceiptHeader::all();
        return DataTables::of($itemReceipts)
            ->setTransformer(new ItemReceiptTransformer())
            ->addIndexColumn()
            ->make(true);
    }
}
