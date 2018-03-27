<?php
/**
 * Created by PhpStorm.
 * User: hellb
 * Date: 2/18/2018
 * Time: 4:45 PM
 */

namespace App\Http\Controllers\Admin\Purchasing;


use App\Http\Controllers\Controller;
use App\Libs\Utilities;
use App\Models\NumberingSystem;
use App\Models\PurchaseOrderDetail;
use App\Models\PurchaseOrderHeader;
use App\Models\PurchaseRequestHeader;
use App\Transformer\Purchasing\PurchaseOrderHeaderTransformer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use PDF;

class PurchaseOrderHeaderController extends Controller
{
    public function index(){

        return View('admin.purchasing.purchase_orders.index');
    }

    public function beforeCreate(){

        return View('admin.purchasing.purchase_orders.before_create');
    }

    public function show(PurchaseOrderHeader $purchase_order){
        $date = Carbon::parse($purchase_order->date)->format('d M Y');

        $data = [
            'header'    => $purchase_order,
            'date'      => $date
        ];

        return View('admin.purchasing.purchase_orders.show')->with($data);
    }

    public function create(){
        if(empty(request()->pr)){
            return redirect()->route('admin.purchase_orders.before_create');
        }

        $purchaseRequest = PurchaseRequestHeader::find(request()->pr);
        $quotation = null;

        // Numbering System
        $sysNo = NumberingSystem::where('doc_id', '4')->first();
        $autoNumber = Utilities::GenerateNumberPurchaseOrder('PO', $sysNo->next_no);

        $data = [
            'purchaseRequest'   => $purchaseRequest,
            'quotation'         => $quotation,
            'autoNumber'        => $autoNumber
        ];

        return View('admin.purchasing.purchase_orders.create')->with($data);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'po_code'       => 'required|max:45|regex:/^\S*$/u',
            'date'          => 'required'
        ],[
            'po_code.regex'     => 'Nomor PO harus tanpa spasi!'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Validate PO number
        if(empty(Input::get('auto_number')) && (empty(Input::get('po_code')) || Input::get('po_code') == "")){
            return redirect()->back()->withErrors('Nomor PO wajib diisi!', 'default')->withInput($request->all());
        }

        // Validate PR number
//        if(empty(Input::get('pr_code')) && empty(Input::get('pr_id'))){
//            return redirect()->back()->withErrors('Nomor PR wajib diisi!', 'default')->withInput($request->all());
//        }

        // Generate auto number
        $poCode = 'default';
        if(Input::get('auto_number')){
            $sysNo = NumberingSystem::where('doc_id', '4')->first();
            $poCode = Utilities::GenerateNumberPurchaseOrder($sysNo->document->code, $sysNo->next_no);
            $sysNo->next_no++;
            $sysNo->save();
        }
        else{
            $poCode = Input::get('po_code');
        }

        // Get PR id
        $prId = $request->input('pr_id');

        // Check existing number
        $temp = PurchaseOrderHeader::where('code', $poCode)->first();
        if(!empty($temp)){
            return redirect()->back()->withErrors('Nomor PO sudah terdaftar!', 'default')->withInput($request->all());
        }

        // Validate details
        $items = $request->input('item_value');
        $qtys = $request->input('qty');
        $prices = $request->input('price');
        $valid = true;
        $i = 0;
        foreach($items as $item){
            if(empty($item)) $valid = false;
            if(empty($qtys[$i]) || $qtys[$i] == '0') $valid = false;
            if(empty($prices[$i]) || $prices[$i] == '0') $valid = false;
            $i++;
        }

        if(!$valid){
            return redirect()->back()->withErrors('Detail barang, jumlah dan harga wajib diisi!', 'default')->withInput($request->all());
        }

        // Validate PR relationship
        $validItem = true;
        $validQty = true;
        $i = 0;
        $purchaseRequest = PurchaseRequestHeader::find($prId);
        foreach($items as $item){
            if(!empty($item)){
                $prDetail = $purchaseRequest->purchase_request_details->where('item_id', $item)->first();
                if(empty($prDetail)){
                    $validItem = false;
                    break;
                }
                else{
                    if($qtys[$i] > $prDetail->quantity){
                        $validQty = false;
                        break;
                    }
                }
                $i++;
            }
        }

        if(!$validItem){
            return redirect()->back()->withErrors('Inventory tidak ada dalam PR!', 'default')->withInput($request->all());
        }
        if(!$validQty){
            return redirect()->back()->withErrors('Kuantitas inventory melebihi kuantitas PR!', 'default')->withInput($request->all());
        }

        $user = \Auth::user();
        $now = Carbon::now('Asia/Jakarta');

        $poHeader = PurchaseOrderHeader::create([
            'code'                  => $poCode,
            'purchase_request_id'   => $prId,
            'supplier_id'           => $request->input('supplier'),
            'status_id'             => 3,
            'created_by'            => $user->id,
            'created_at'            => $now->toDateTimeString()
        ]);

        $delivery = 0;
        if($request->filled('delivery_fee') && $request->input('delivery_fee') != '0'){
            $deliveryFee = str_replace('.','', $request->input('delivery_fee'));
            $delivery = (double) $deliveryFee;
            $poHeader->delivery_fee = $deliveryFee;
        }

        $date = Carbon::createFromFormat('d M Y', $request->input('date'), 'Asia/Jakarta');
        $poHeader->date = $date->toDateTimeString();

        $poHeader->save();

        // Create po detail
        $totalPrice = 0;
        $totalDiscount = 0;
        $totalPayment = 0;
        $discounts = Input::get('discount');
        $remarks = Input::get('remark');
        $idx = 0;

        foreach($items as $item){
            if(!empty($item)){
                $priceStr = str_replace('.','', $prices[$idx]);
                $price = (double) $priceStr;
                $qty = (double) $qtys[$idx];
                $poDetail = PurchaseOrderDetail::create([
                    'header_id'         => $poHeader->id,
                    'item_id'           => $item,
                    'quantity'          => $qty,
                    'quantity_invoiced' => 0,
                    'price'             => $priceStr
                ]);

                // Check discount
                if(!empty($discounts[$idx]) && $discounts[$idx] !== '0'){
                    $poDetail->discount = $discounts[$idx];

                    $discount = (double) $discounts[$idx];
                    $discountAmount = ($qty * $price) * $discount / 100;
                    $poDetail->subtotal = ($qty * $price) - $discountAmount;

                    // Accumulate total price
                    $totalPrice += $qty * $price;

                    // Accumulate total discount
                    $totalDiscount += $discountAmount;
                }
                else{
                    $poDetail->subtotal = $qty * $price;
                    $totalPrice += $qty * $price;
                }

                if(!empty($remarks[$idx])) $poDetail->remark = $remarks[$idx];
                $poDetail->save();

                // Accumulate subtotal
                $totalPayment += $poDetail->subtotal;
            }
            $idx++;
        }

        if($totalDiscount > 0) $poHeader->total_discount = $totalDiscount;
        $totalPayment += $delivery;
        $poHeader->total_price = $totalPrice;

        // Save total payment without tax
        $poHeader->total_payment_before_tax = $totalPayment;

        // Get PPN & PPh
        $ppnAmount = 0;
        if($request->filled('ppn') && $request->input('ppn') != '0'){
            $ppnAmount = $totalPayment * (10 / 100);
            $poHeader->ppn_percent = 10;
            $poHeader->ppn_amount = $ppnAmount;
        }
        $pphAmount = 0;
        if($request->filled('pph') && $request->input('pph') != '0'){
            $pph = str_replace('.','', $request->input('pph'));
            $pphAmount = (double) $pph;
            $poHeader->pph_amount = $pphAmount;
        }

        $poHeader->total_payment = $totalPayment + $ppnAmount - $pphAmount;
        $poHeader->save();

        Session::flash('message', 'Berhasil membuat purchase order!');

        return redirect()->route('admin.purchase_orders.show', ['purchase_order' => $poHeader]);
    }

    public function edit(PurchaseOrderHeader $purchase_order){
        $date = Carbon::parse($purchase_order->date)->format('d M Y');

        $data = [
            'header'    => $purchase_order,
            'date'      => $date
        ];

        return View('admin.purchasing.purchase_orders.edit')->with($data);
    }

    public function update(Request $request, PurchaseOrderHeader $purchase_order){
        $validator = Validator::make($request->all(),[
            'date'          => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        if($request->filled('supplier')) $purchase_order->supplier_id = Input::get('supplier');

        $date = Carbon::createFromFormat('d M Y', $request->input('date'), 'Asia/Jakarta');
        $purchase_order->date = $date->toDateTimeString();

        $totalPaymentWithoutTax = $purchase_order->total_payment_before_tax;

        $oldDelivery = $purchase_order->delivery_fee ?? 0;
        $newDelivery = 0;
        if($request->filled('delivery_fee') && $request->input('delivery_fee') != '0'){
            $deliveryFee = str_replace('.','', $request->input('delivery_fee'));
            $newDelivery = (double) $deliveryFee;
            $purchase_order->delivery_fee = $deliveryFee;
        }
        else{
            $purchase_order->delivery_fee = null;
        }
        $totalPayment = $totalPaymentWithoutTax - $oldDelivery + $newDelivery;
        $purchase_order->total_payment_before_tax = $totalPayment;

        // Get PPN & PPh
        $ppnAmount = 0;
        if($request->filled('ppn')){
            $ppnAmount = $totalPayment * (10 / 100);
            $purchase_order->ppn_percent = 10;
            $purchase_order->ppn_amount = $ppnAmount;
        }
        else{
            $purchase_order->ppn_percent = null;
            $purchase_order->ppn_amount = null;
        }

        $pphAmount = 0;
        if($request->filled('pph')){
            $pph = str_replace('.','', $request->input('pph'));
            $pphAmount = (double) $pph;
            $purchase_order->pph_amount = $pphAmount;
        }
        else{
            $purchase_order->pph_percent = null;
            $purchase_order->pph_amount = null;
        }

        $purchase_order->total_payment = $totalPayment + $ppnAmount - $pphAmount;
        $purchase_order->save();

        Session::flash('message', 'Berhasil ubah purchase order!');

        return redirect()->route('admin.purchase_orders.show', ['purchase_order' => $purchase_order]);
    }

    public function close(Request $request){
        try{
            $user = Auth::user();
            $now = Carbon::now('Asia/Jakarta');

            $purchaseOrder = PurchaseOrderHeader::find($request->input('id'));
            $purchaseOrder->closed_by = $user->id;
            $purchaseOrder->closing_date = $now->toDateTimeString();
            $purchaseOrder->close_reason = $request->input('reason');
            $purchaseOrder->status_id = 11;
            $purchaseOrder->save();

            Session::flash('message', 'Berhasil tutup PO!');

            return Response::json(array('success' => 'VALID'));
        }
        catch(\Exception $ex){
            return Response::json(array('errors' => 'INVALID'));
        }
    }

    public function report(){
        return View('admin.purchasing.purchase_orders.report');
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

        $tempStart = strtotime($request->input('start_date'));
        $start = date('Y-m-d', $tempStart);
        $tempEnd = strtotime($request->input('end_date'));
        $end = date('Y-m-d', $tempEnd);

        // Validate date
        if($start > $end){
            return redirect()->back()->withErrors('Dari Tanggal tidak boleh lebih besar dari Sampai Tanggal!', 'default')->withInput($request->all());
        }

        $data = PurchaseOrderHeader::whereBetween('created_at', array($start, $end));

        // Filter status
        $status = $request->input('status');
        if($status != '0'){
            $data = $data->where('status_id', $status);
        }

        $data = $data->orderByDesc('date')
            ->get();

        // Validate Data
        if(empty($data) || $data->count() == 0){
            return redirect()->back()->withErrors('Data tidak ditemukan!', 'default')->withInput($request->all());
        }

        $total = $data->sum('total_payment');
        $totalStr = number_format($total, 0, ",", ".");

        $pdf = PDF::loadView('documents.purchase_orders.purchase_orders_pdf', ['data' => $data, 'start_date' => $request->input('start_date'), 'finish_date' => $request->input('end_date'), 'total' => $totalStr])
            ->setPaper('a4', 'landscape');
        $now = Carbon::now('Asia/Jakarta');
        $filename = 'PURCHASE_ORDER_REPORT_' . $now->toDateTimeString();

        return $pdf->download($filename.'.pdf');
    }

    public function getIndex(Request $request){
        try{
            $purchaseOrders = null;

            $mode = 'default';
            if($request->filled('mode')){
                $mode = $request->input('mode');

                if($mode == 'before_create_po'){
                    $purchaseOrders = PurchaseOrderHeader::dateDescending()->get();
                }
                else{
                    $purchaseOrders = PurchaseOrderHeader::where('status_id', 3)
                        ->dateDescending()
                        ->get();
                }
            }
            else{
                $purchaseOrders = PurchaseOrderHeader::where('status_id', 3)
                    ->dateDescending()
                    ->get();
            }

            return DataTables::of($purchaseOrders)
                ->setTransformer(new PurchaseOrderHeaderTransformer($mode))
                ->addIndexColumn()
                ->make(true);
        }
        catch(\Exception $ex){
            error_log($ex);
        }
    }

    public function printDocument($id){
        $purchaseOrder = PurchaseOrderHeader::find($id);
        $purchaseOrderDetails = PurchaseOrderDetail::where('header_id', $purchaseOrder->id)->get();
        $dateNow = Carbon::now('Asia/Jakarta');
        $now = $dateNow->format('d-M-Y');

        $data = [
            'purchaseOrder'         => $purchaseOrder,
            'purchaseOrderDetails'  => $purchaseOrderDetails,
            'now'               => $now
        ];

        return view('documents.purchase_orders.purchase_orders_doc')->with($data);
    }

    public function download($id){
        $purchaseOrder = PurchaseOrderHeader::find($id);
        $purchaseOrderDetails = PurchaseOrderDetail::where('header_id', $purchaseOrder->id)->get();

        $pdf = PDF::loadView('documents.purchase_orders.purchase_orders_doc', ['purchaseOrder' => $purchaseOrder, 'purchaseOrderDetails' => $purchaseOrderDetails])->setPaper('A4');
        $now = Carbon::now('Asia/Jakarta');
        $filename = $purchaseOrder->code. '_' . $now->toDateTimeString();

        return $pdf->download($filename.'.pdf');
    }

    public function getPurchaseOrders(Request $request){
        $term = trim($request->q);
        $purchase_requests = PurchaseOrderHeader::where('status_id', 3)
            ->where('code', 'LIKE', '%'. $term. '%')
            ->get();

        $formatted_tags = [];

        foreach ($purchase_requests as $purchase_request) {
            $formatted_tags[] = ['id' => $purchase_request->id, 'text' => $purchase_request->code];
        }

        return \Response::json($formatted_tags);
    }
}