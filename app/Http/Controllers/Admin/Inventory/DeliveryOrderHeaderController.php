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
    public function index(){
        return View('admin.inventory.delivery_orders.index');
    }

    public function create(){
        $sites = Site::all();

        // Get PR data if exist
        $purchaseRequest = null;
        if(!empty(request()->pr)){
            $purchaseRequest = PurchaseRequestHeader::find(request()->pr);
        }

        // Numbering System
        $sysNo = NumberingSystem::where('doc_id', '8')->first();
        $autoNumber = Utilities::GenerateNumber($sysNo->document->code, $sysNo->next_no);

        $data =[
            'sites'             => $sites,
            'autoNumber'        => $autoNumber,
            'purchaseRequest'   => $purchaseRequest
        ];

        return View('admin.inventory.delivery_orders.create')->with($data);
    }

    public function show(DeliveryOrderHeader $delivery_order){
        $header = $delivery_order;

        return View('admin.inventory.delivery_orders.show', compact('header'));
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'code'           => 'max:30|regex:/^\S*$/u|unique:delivery_order_headers',
            'remark_header'  => 'max:150',
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
        if(empty($request->input('pr_code')) && empty($request->input('pr_id'))){
            return redirect()->back()->withErrors('Nomor PR wajib diisi!', 'default')->withInput($request->all());
        }

        // Validate from & to site
        if($request->input('from_site') === '-1' || $request->input('to_site') === '-1'){
            return redirect()->back()->withErrors('Pilih site keberangkatan & tujuan!', 'default')->withInput($request->all());
        }

        if($request->input('from_site') === $request->input('to_site')){
            return redirect()->back()->withErrors('Site keberangkatan & tujuan harus berbeda!', 'default')->withInput($request->all());
        }

        // Generate auto number
        $doCode = 'default';
        if($request->input('auto_number')){
            $sysNo = NumberingSystem::where('doc_id', '8')->first();
            $doCode = Utilities::GenerateNumber($sysNo->document->code, $sysNo->next_no);
            $sysNo->next_no++;
            $sysNo->save();
        }
        else{
            $doCode = $request->input('do_code');
        }

        // Get PR id
        $prId = '0';
        if($request->filled('pr_code')){
            $prId = $request->input('pr_code');
        }
        else{
            $prId = $request->input('pr_id');
        }

        // Check existing number
        $temp = DeliveryOrderHeader::where('code', $doCode)->first();
        if(!empty($temp)){
            return redirect()->back()->withErrors('Nomor Surat Jalan sudah terpakai!', 'default')->withInput($request->all());
        }

        // Validate details
        $items = $request->input('item');

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
        $site = Site::find($request->input('from_site'));
        $i = 0;
        foreach($items as $item){
            if(!empty($item)){
                $valid = ItemStock::where('warehouse_id', $site->warehouse_id)
                    ->where('item_id', $item)
                    ->where('stock', '>', $qtys[$i])
                    ->exists();
            }
            $i++;
        }

        if(!$valid){
            return redirect()->back()->withErrors('Stok barang kosong atau tidak ada!', 'default')->withInput($request->all());
        }

        $user = \Auth::user();
        $now = Carbon::now('Asia/Jakarta');

        $doHeader = DeliveryOrderHeader::create([
            'code'                  => $doCode,
            'purchase_request_id'   => $prId,
            'from_site_id'          => $request->input('from_site'),
            'to_site_id'            => $request->input('to_site'),
            'status_id'             => 3,
            'created_by'            => $user->id,
            'created_at'            => $now->toDateTimeString(),
            'updated_by'            => $user->id
        ]);

        if($request->filled('pr_code')){
            $doHeader->purchase_request_id = $request->input('pr_code');
        }
        else{
            $doHeader->purchase_request_id = $request->input('pr_id');
        }

        if(!empty($request->input('machinery'))){
            $doHeader->machinery_id = $request->input('machinery');
        }

        if($request->filled('remark')){
            $doHeader->remark = $request->input('remark_header');
        }

        $doHeader->save();

        // Create delivery order detail
        $remarks = $request->input('remark');
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
                $stock = ItemStock::where('warehouse_id', $site->warehouse_id)
                    ->where('item_id', $item)
                    ->where('stock', '>', $qtys[$idx])
                    ->first();
                $stock->stock -= intval($qtys[$idx]);
                $stock->save();

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
        $sites = Site::all();

        $data = [
            'header'    => $header,
            'sites'     => $sites
        ];

        return View('admin.inventory.delivery_orders.edit')->with($data);
    }

    public function update(Request $request, DeliveryOrderHeader $delivery_order){
        $validator = Validator::make($request->all(),[
            'remark'        => 'max:150'
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
        $delivery_order->save();

        Session::flash('message', 'Berhasil ubah Surat Jalan!');

        return redirect()->route('admin.delivery_orders.show', ['delivery_order' => $delivery_order]);
    }

    public function confirm(Request $request){
        try{
            $user = \Auth::user();
            $now = Carbon::now('Asia/Jakarta');

            $header = DeliveryOrderHeader::find($request->input('id'));

            foreach($header->delivery_order_details as $detail){


                // Decrease transport warehouse stock
                $stockTransport = ItemStock::where('warehouse_id', 0)
                    ->where('item_id', $detail->item_id)
                    ->first();
                $stockTransport->stock -= $detail->quantity;
                $stockTransport->save();

                // Increase arrival warehouse stock
                $stockArrival = ItemStock::where('warehouse_id', $header->toSite->warehouse_id)
                    ->where('item_id', $detail->item_id)
                    ->first();
                if(!empty($stockArrival)){
                    $stockArrival->stock += $detail->quantity;
                    $stockArrival->updated_at = $now->toDateTimeString();
                    $stockArrival->updated_by = $user->id;
                }
                else{
                    $newStock = new ItemStock();
                    $newStock->warehouse_id = $header->toSite->warehouse_id;
                    $newStock->item_id = $detail->item_id;
                    $newStock->stock = $detail->quantity;
                    $newStock->created_by = $user->id;
                    $newStock->created_at = $now->toDateTimeString();
                    $newStock->updated_by = $user->id;
                    $newStock->save();
                }
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
            return Response::json(array('errors' => 'INVALID'));
        }
    }

    public function cancel(Request $request){
        try{
            $user = \Auth::user();
            $now = Carbon::now('Asia/Jakarta');

            $header = DeliveryOrderHeader::find($request->input('id'));

            foreach($header->delivery_order_details as $detail){
                // Decrease transport warehouse stock
                $stockTransport = ItemStock::where('warehouse_id', 0)
                    ->where('item_id', $detail->item_id)
                    ->first();
                $stockTransport->stock -= $detail->quantity;
                $stockTransport->save();

                // Restore departure warehouse stock
                $stockArrival = ItemStock::where('warehouse_id', $header->fromSite->warehouse_id)
                    ->where('item_id', $detail->item_id)
                    ->first();
                if(!empty($stockArrival)){
                    $stockArrival->stock += $detail->quantity;
                    $stockArrival->updated_at = $now->toDateTimeString();
                    $stockArrival->updated_by = $user->id;
                }
                else{
                    $newStock = new ItemStock();
                    $newStock->warehouse_id = $header->toSite->warehouse_id;
                    $newStock->item_id = $detail->item_id;
                    $newStock->stock = $detail->quantity;
                    $newStock->created_by = $user->id;
                    $newStock->created_at = $now->toDateTimeString();
                    $newStock->updated_by = $user->id;
                    $newStock->save();
                }
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
            return Response::json(array('errors' => 'INVALID'));
        }
    }

    public function report(){
        return View('admin.inventory.delivery_orders.report');
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

        $data = DeliveryOrderHeader::whereBetween('created_at', array($start, $end))->get();

        //Check Data
        if($data == null || $data->count() == 0){
            return redirect()->back()->withErrors('Data Tidak Ditemukan!', 'default')->withInput($request->all());
        }

        $pdf = PDF::loadView('documents.delivery_orders.delivery_orders_pdf', ['data' => $data, 'start_date' => Input::get('start_date'), 'finish_date' => Input::get('end_date')])
            ->setPaper('a4', 'landscape');
        $now = Carbon::now('Asia/Jakarta');
        $filename = 'DELIVERY_ORDER_REPORT_' . $now->toDateTimeString();

        return $pdf->download($filename.'.pdf');
    }

    public function getIndex(){
        $deliveryOrders = DeliveryOrderHeader::dateDescending()->get();
        return DataTables::of($deliveryOrders)
            ->setTransformer(new DeliveryOrderHeaderTransformer)
            ->addIndexColumn()
            ->make(true);
    }
}