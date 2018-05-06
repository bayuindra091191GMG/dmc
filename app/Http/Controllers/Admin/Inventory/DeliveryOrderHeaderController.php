<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 02/03/2018
 * Time: 14:56
 */

namespace App\Http\Controllers\Admin\Inventory;


use App\Http\Controllers\Controller;
use App\Libs\Utilities;
use App\Models\DeliveryOrderDetail;
use App\Models\DeliveryOrderHeader;
use App\Models\Item;
use App\Models\ItemStock;
use App\Models\NumberingSystem;
use App\Models\PurchaseRequestHeader;
use App\Models\Site;
use App\Models\StockCard;
use App\Models\Warehouse;
use App\Transformer\Inventory\DeliveryOrderHeaderTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use PDF;

class DeliveryOrderHeaderController extends Controller
{
    public function index(Request $request){
        $filterStatus = '3';
        if($request->status != null){
            $filterStatus = $request->status;
        }

        return View('admin.inventory.delivery_orders.index', compact('filterStatus'));
    }

    public function create(){
        $warehouses = Warehouse::where('id', '>', 0)->get();

        // Get PR data if exist
        $purchaseRequest = null;
        if(!empty(request()->pr)){
            $purchaseRequest = PurchaseRequestHeader::find(request()->pr);
        }

        // Numbering System
        $sysNo = NumberingSystem::where('doc_id', '8')->first();
        $autoNumber = Utilities::GenerateNumber($sysNo->document->code, $sysNo->next_no);

        $data =[
            'warehouses'        => $warehouses,
            'autoNumber'        => $autoNumber,
            'purchaseRequest'   => $purchaseRequest
        ];

        return View('admin.inventory.delivery_orders.create')->with($data);
    }

    public function show(DeliveryOrderHeader $delivery_order){
        $header = $delivery_order;
        $date = Carbon::parse($delivery_order->date)->format('d M Y');

        $data = [
            'header'    => $header,
            'date'      => $date
        ];

        return View('admin.inventory.delivery_orders.show')->with($data);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'do_code'        => 'required|max:30|regex:/^\S*$/u',
            'remark_header'  => 'max:150',
            'date'           => 'required'
        ],[
            'code.regex'     => 'Nomor Surat Jalan harus tanpa spasi'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Validate DO number
        if(empty($request->input('auto_number')) && (empty($request->input('do_code')) || $request->input('do_code') == "")){
            return redirect()->back()->withErrors('Nomor Surat Jalan wajib diisi!', 'default')->withInput($request->all());
        }

        // Validate PR number
//        if(empty($request->input('pr_code')) && empty($request->input('pr_id'))){
//            return redirect()->back()->withErrors('Nomor PR wajib diisi!', 'default')->withInput($request->all());
//        }

        // Validate from & to warehouse
        if($request->input('from_warehouse') === '-1' || $request->input('to_warehouse') === '-1'){
            return redirect()->back()->withErrors('Pilih gudang keberangkatan & tujuan!', 'default')->withInput($request->all());
        }

        if($request->input('from_warehouse') === $request->input('to_warehouse')){
            return redirect()->back()->withErrors('Gudang keberangkatan & tujuan harus berbeda!', 'default')->withInput($request->all());
        }

        // Validate details
        $items = $request->input('item');
        $remarks = $request->input('remark');

        if(count($items) == 0){
            return redirect()->back()->withErrors('Detail barang wajib diisi!', 'default')->withInput($request->all());
        }

        $qtys = $request->input('qty');
        $valid = true;
        $i = 0;
        foreach($items as $item){
            if(empty($item)) $valid = false;
            if(empty($qtys[$i]) || $qtys[$i] == '0') $valid = false;
            $i++;
        }

        if(!$valid){
            return redirect()->back()->withErrors('Detail barang dan jumlah wajib diisi!', 'default')->withInput($request->all());
        }

        $valid = true;
        // Validate stock
        $warehouse = Warehouse::find($request->input('from_warehouse'));
        $i = 0;
        foreach($items as $item){
            if(!empty($item)){
                $valid = ItemStock::where('warehouse_id', $warehouse->id)
                    ->where('item_id', $item)
                    ->where('stock', '>', $qtys[$i])
                    ->exists();
            }
            $i++;
        }

        if(!$valid){
            return redirect()->back()->withErrors('Stok barang kosong atau tidak ada!', 'default')->withInput($request->all());
        }

        // Generate auto number
        $doCode = 'default';
        if($request->input('auto_number')){
            $sysNo = NumberingSystem::where('doc_id', '8')->first();
            $doCode = Utilities::GenerateNumber($sysNo->document->code, $sysNo->next_no);

            // Check existing number
            $temp = DeliveryOrderHeader::where('code', $doCode)->first();
            if(!empty($temp)){
                return redirect()->back()->withErrors('Nomor Surat Jalan sudah terpakai!', 'default')->withInput($request->all());
            }

            $sysNo->next_no++;
            $sysNo->save();
        }
        else{
            $doCode = $request->input('do_code');

            // Check existing number
            $temp = DeliveryOrderHeader::where('code', $doCode)->first();
            if(!empty($temp)){
                return redirect()->back()->withErrors('Nomor Surat Jalan sudah terpakai!', 'default')->withInput($request->all());
            }
        }

        // Get PR id
        $prId = '0';
        if($request->filled('pr_code')){
            $prId = $request->input('pr_code');
        }
        else{
            if($request->filled('pr_id')){
                $prId = $request->input('pr_id');
            }
        }

        $user = \Auth::user();
        $now = Carbon::now('Asia/Jakarta');

        $doHeader = DeliveryOrderHeader::create([
            'code'                  => $doCode,
            'from_warehouse_id'     => $request->input('from_warehouse'),
            'to_warehouse_id'       => $request->input('to_warehouse'),
            'status_id'             => 3,
            'created_by'            => $user->id,
            'created_at'            => $now->toDateTimeString(),
            'updated_by'            => $user->id
        ]);

        $doHeader->purchase_request_id = $prId !== '0' ? $prId : null;

        if($request->filled('machinery')){
            $doHeader->machinery_id = $request->input('machinery');
        }
        else{
            if($request->filled('machinery_id')){
                $doHeader->machinery_id = $request->input('machinery_id');
            }
        }

        if($request->filled('remark')){
            $doHeader->remark = $request->input('remark_header');
        }

        $date = Carbon::createFromFormat('d M Y', $request->input('date'), 'Asia/Jakarta');
        $doHeader->date = $date->toDateTimeString();

        $doHeader->save();

        // Create delivery order detail
        $idx = 0;
        foreach($request->input('item') as $item){
            if(!empty($item)){
                $doDetail = DeliveryOrderDetail::create([
                    'header_id'     => $doHeader->id,
                    'item_id'       => $item,
                    'quantity'      => $qtys[$idx]
                ]);

                if(!empty($remarks[$idx])) {
                    $doDetail->remark = $remarks[$idx];
                    $doDetail->save();
                }

                // Change stock
                $stock = ItemStock::where('warehouse_id', $warehouse->id)
                    ->where('item_id', $item)
                    ->where('stock', '>', $qtys[$idx])
                    ->first();
                $stock->stock -= intval($qtys[$idx]);
                $stock->save();

                // Add stock card
                $stockCard = StockCard::create([
                    'item_id'       => $item,
                    'change'        => $qtys[$idx],
                    'stock'         => $stock->stock,
                    'flag'          => '-',
                    'description'   => 'Surat Jalan '. $doHeader->code,
                    'warehouse_id'  => $warehouse->id,
                    'created_by'    => $user->id,
                    'created_at'    => $now->toDateTimeString()
                ]);

                // Entry to Transport Warehouse
                $transportStock = ItemStock::where('warehouse_id', 0)
                    ->where('item_id', $item)
                    ->first();
                if(!empty($transportStock)){
                    $transportStock->stock += intval($qtys[$idx]);
                    $transportStock->updated_by = $user->id;
                    $transportStock->updated_at = $now->toDateTimeString();
                    $transportStock->save();
                }
                else{
                    $newTransportStock = ItemStock::create([
                        'item_id'       => $item,
                        'warehouse_id'  => 0,
                        'stock'         => $qtys[$idx],
                        'created_by'    => $user->id,
                        'created_at'    => $now->toDateTimeString(),
                        'updated_by'    => $user->id
                    ]);
                }
            }
            $idx++;
        }

        Session::flash('message', 'Berhasil membuat surat jalan!');

        return redirect()->route('admin.delivery_orders.show', ['delivery_order' => $doHeader]);
    }

    public function edit(DeliveryOrderHeader $delivery_order){
        $header = $delivery_order;
        $warehouses = Warehouse::where('id', '>', 0)->get();
        $date = Carbon::parse($delivery_order->date)->format('d M Y');

        $data = [
            'header'        => $header,
            'warehouses'    => $warehouses,
            'date'          => $date
        ];

        return View('admin.inventory.delivery_orders.edit')->with($data);
    }

    public function update(Request $request, DeliveryOrderHeader $delivery_order){
        $validator = Validator::make($request->all(),[
            'remark'        => 'max:150',
            'date'          => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = \Auth::user();
        $now = Carbon::now('Asia/Jakarta');

        $delivery_order->remark = $request->input('remark');
        $delivery_order->updated_by = $user->id;
        $delivery_order->updated_at = $now->toDateTimeString();

        $date = Carbon::createFromFormat('d M Y', $request->input('date'), 'Asia/Jakarta');
        $delivery_order->date = $date->toDateTimeString();

        $delivery_order->save();

        Session::flash('message', 'Berhasil ubah Surat Jalan!');

        return redirect()->route('admin.delivery_orders.show', ['delivery_order' => $delivery_order]);
    }

    public function confirm(Request $request){
        try{
            $user = \Auth::user();
            $now = Carbon::now('Asia/Jakarta');

            $header = DeliveryOrderHeader::find($request->input('id'));

            // Validate status
            if($header->status_id != 3){
                return Response::json(array('errors' => 'INVALID'));
            }

            foreach($header->delivery_order_details as $detail){

                // Decrease transport warehouse stock
                $stockTransport = ItemStock::where('warehouse_id', 0)
                    ->where('item_id', $detail->item_id)
                    ->first();
                $stockTransport->stock -= $detail->quantity;
                $stockTransport->save();

                // Increase arrival warehouse stock
                $stockArrival = ItemStock::where('warehouse_id', $header->to_warehouse_id)
                    ->where('item_id', $detail->item_id)
                    ->first();

                $stockResult = 0;
                if(!empty($stockArrival)){
                    $stockArrival->stock += $detail->quantity;
                    $stockArrival->updated_at = $now->toDateTimeString();
                    $stockArrival->updated_by = $user->id;

                    $stockResult = $stockArrival->stock;
                }
                else{
                    $newStock = new ItemStock();
                    $newStock->warehouse_id = $header->to_warehouse_id;
                    $newStock->item_id = $detail->item_id;
                    $newStock->stock = $detail->quantity;
                    $newStock->created_by = $user->id;
                    $newStock->created_at = $now->toDateTimeString();
                    $newStock->updated_by = $user->id;
                    $newStock->save();

                    $stockResult = $newStock->stock;
                }

                // Add stock card
                $stockCard = StockCard::create([
                    'item_id'       => $detail->item_id,
                    'change'        => $detail->quantity,
                    'stock'         => $stockResult,
                    'flag'          => '+',
                    'description'   => 'Surat Jalan '. $header->code,
                    'warehouse_id'  => $header->to_warehouse_id,
                    'created_by'    => $user->id,
                    'created_at'    => $now->toDateTimeString()
                ]);
            }

            $header->status_id = 4;
            $header->confirm_by = $user->id;
            $header->confirm_date = $now->toDateTimeString();
            $header->updated_by = $user->id;
            $header->updated_at = $now->toDateTimeString();
            $header->save();

            Session::flash('message', 'Berhasil konfirmasi barang datang pada Surat Jalan '. $header->code);

            return Response::json(array('success' => 'VALID'));
        }
        catch(\Exception $ex){
            error_log($ex);
            return Response::json(array('errors' => 'INVALID'));
        }
    }

    public function cancel(Request $request){
        try{
            $user = \Auth::user();
            $now = Carbon::now('Asia/Jakarta');

            $header = DeliveryOrderHeader::find($request->input('id'));

            // Validate status
            if($header->status_id != 3){
                return Response::json(array('errors' => 'INVALID'));
            }

            foreach($header->delivery_order_details as $detail){
                // Decrease transport warehouse stock
                $stockTransport = ItemStock::where('warehouse_id', 0)
                    ->where('item_id', $detail->item_id)
                    ->first();
                $stockTransport->stock -= $detail->quantity;
                $stockTransport->save();

                // Restore from warehouse stock
                $stockArrival = ItemStock::where('warehouse_id', $header->from_warehouse_id)
                    ->where('item_id', $detail->item_id)
                    ->first();
                if(!empty($stockArrival)){
                    $stockArrival->stock += $detail->quantity;
                    $stockArrival->updated_at = $now->toDateTimeString();
                    $stockArrival->updated_by = $user->id;
                }
                else{
                    $newStock = new ItemStock();
                    $newStock->warehouse_id = $header->from_warehouse_id;
                    $newStock->item_id = $detail->item_id;
                    $newStock->stock = $detail->quantity;
                    $newStock->created_by = $user->id;
                    $newStock->created_at = $now->toDateTimeString();
                    $newStock->updated_by = $user->id;
                    $newStock->save();
                }
            }

            $header->status_id = 5;
            $header->confirm_by = $user->id;
            $header->confirm_date = $now->toDateTimeString();
            $header->updated_by = $user->id;
            $header->updated_at = $now->toDateTimeString();
            $header->save();

            Session::flash('message', 'Berhasil konfirmasi barang datang pada Surat Jalan '. $header->code);

            return Response::json(array('success' => 'VALID'));
        }
        catch(\Exception $ex){
            error_log($ex);
            return Response::json(array('errors' => 'INVALID'));
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getIndex(Request $request){

        $status = '0';
        if($request->filled('status')){
            $status = $request->input('status');
            if($status != '0'){
                $deliveryOrders = DeliveryOrderHeader::where('status_id', $status)
                    ->dateDescending()
                    ->get();
            }
            else{
                $deliveryOrders = DeliveryOrderHeader::dateDescending()->get();
            }
        }
        else{
            $deliveryOrders = DeliveryOrderHeader::dateDescending()->get();
        }

        return DataTables::of($deliveryOrders)
            ->setTransformer(new DeliveryOrderHeaderTransformer)
            ->addIndexColumn()
            ->make(true);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getDeliveryOrders(Request $request){
        $term = trim($request->q);
        $deliveries = DeliveryOrderHeader::where('code', 'LIKE', '%'. $term. '%')
            ->get();

        $formatted_tags = [];

        foreach ($deliveries as $delivery) {
            $formatted_tags[] = ['id' => $delivery->id, 'text' => $delivery->code];
        }

        return Response::json($formatted_tags);
    }

    public function report(){
        return View('admin.inventory.delivery_orders.report');
    }

    public function downloadReport(Request $request) {
        $validator = Validator::make($request->all(),[
            'start_date'        => 'required',
            'end_date'          => 'required',
        ],[
            'start_date.required'   => 'Dari Tanggal wajib diisi!',
            'end_date.required'     => 'Sampai Tanggal wajib diisi!',

        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $tempStart = strtotime(Input::get('start_date'));
        $start = date('Y-m-d', $tempStart);
        $tempEnd = strtotime(Input::get('end_date'));
        $end = date('Y-m-d', $tempEnd);

        //Check date
        if($start > $end){
            return redirect()->back()->withErrors('Dari Tanggal tidak boleh lebih besar dari Sampai Tanggal!', 'default')->withInput($request->all());
        }

        $data = DeliveryOrderHeader::whereBetween('created_at', array($start, $end));

        // Filter status
        $status = $request->input('status');
        if($status != '0'){
            $data = $data->where('status_id', $status);
        }

        $data = $data->orderByDesc('date')
            ->get();

        // Check Data
        if($data == null || $data->count() == 0){
            return redirect()->back()->withErrors('Data tidak ditemukan!', 'default')->withInput($request->all());
        }

        $pdf = PDF::loadView('documents.delivery_orders.delivery_orders_pdf', ['data' => $data, 'start_date' => Input::get('start_date'), 'finish_date' => Input::get('end_date')])
            ->setPaper('a4', 'landscape');
        $now = Carbon::now('Asia/Jakarta');
        $filename = 'DELIVERY_ORDER_REPORT_' . $now->toDateTimeString();
        $pdf->setOptions(["isPhpEnabled"=>true]);

        return $pdf->download($filename.'.pdf');
    }
}