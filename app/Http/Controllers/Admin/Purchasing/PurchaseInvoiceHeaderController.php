<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 14/03/2018
 * Time: 14:38
 */

namespace App\Http\Controllers\Admin\Purchasing;


use App\Http\Controllers\Controller;
use App\Libs\Utilities;
use App\Models\NumberingSystem;
use App\Models\PurchaseInvoiceDetail;
use App\Models\PurchaseInvoiceHeader;
use App\Models\PurchaseOrderHeader;
use App\Transformer\Purchasing\PurchaseInvoiceHeaderTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class PurchaseInvoiceHeaderController extends Controller
{
    public function index(){

        return View('admin.purchasing.purchase_invoices.index');
    }

    public function show(PurchaseInvoiceHeader $purchase_invoice){
        $header = $purchase_invoice;

        return View('admin.purchasing.purchase_invoices.show', compact('header'));
    }

    public function beforeCreate(){
        return View('admin.purchasing.purchase_invoices.before_create');
    }

    public function create(){
        $purchaseOrder = null;
        if(!empty(request()->po)){
            $purchaseOrder = PurchaseOrderHeader::find(request()->po);
        }

        // Numbering System
        $sysNo = NumberingSystem::where('doc_id', '6')->first();
        $autoNumber = Utilities::GenerateNumber($sysNo->document->code, $sysNo->next_no);

        $data = [
            'purchaseOrder'   => $purchaseOrder,
            'autoNumber'        => $autoNumber
        ];

        return View('admin.purchasing.purchase_invoices.create')->with($data);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'code'      => 'max:45|regex:/^\S*$/u',
            'date'      => 'required'
        ],[
            'code.regex'    => 'Nomor Invoice harus tanpa spasi!'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Validate invoice number
        if(!$request->filled('auto_number') && (!$request->filled('code') || $request->input('code') == "")){
            return redirect()->back()->withErrors('Nomor Invoice wajib diisi!', 'default')->withInput($request->all());
        }

        // Validate PO number
        if(!$request->filled('po_code') && !$request->filled('po_id')){
            return redirect()->back()->withErrors('Nomor PO wajib diisi!', 'default')->withInput($request->all());
        }

        // Generate auto number
        $invCode = 'default';
        if($request->filled('auto_number')){
            $sysNo = NumberingSystem::where('doc_id', '6')->first();
            $invCode = Utilities::GenerateNumberPurchaseOrder($sysNo->document->code, $sysNo->next_no);
            $sysNo->next_no++;
            $sysNo->save();
        }
        else{
            $invCode = $request->input('code');
        }

        // Get PO id
        $poId = '0';
        if($request->filled('po_code')){
            $poId = $request->input('po_code');
        }
        else{
            $poId = $request->input('po_id');
        }

        // Check existing number
//        $temp = PurchaseInvoiceHeader::where('code', $invCode)->first();
        if(PurchaseInvoiceHeader::where('code', $invCode)->exists()){
            return redirect()->back()->withErrors('Nomor Invoice sudah terdaftar!', 'default')->withInput($request->all());
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

        $user = \Auth::user();
        $now = Carbon::now('Asia/Jakarta');

        $invHeader = PurchaseInvoiceHeader::create([
            'code'                  => $invCode,
            'purchase_order_id'     => $poId,
            'status_id'             => 3,
            'created_by'            => $user->id,
            'created_at'            => $now->toDateTimeString()
        ]);

        $delivery = 0;
        if($request->filled('delivery_fee') && $request->input('delivery_fee') != '0'){
            $deliveryFee = str_replace('.','', $request->input('delivery_fee'));
            $delivery = (double) $deliveryFee;
            $invHeader->delivery_fee = $deliveryFee;
        }

        if($request->filled('po_code')){
            $invHeader->purchase_order_id = $request->input('po_code');
        }
        else{
            $invHeader->purchase_order_id = $request->input('po_id');
        }

        $date = Carbon::createFromFormat('d M Y', $request->input('date'), 'Asia/Jakarta');
        $invHeader->date = $date->toDateTimeString();

        $invHeader->save();

        // Create po detail
        $totalPrice = 0;
        $totalDiscount = 0;
        $totalPayment = 0;
        $discounts = $request->input('discount');
        $remarks = $request->input('remark');
        $idx = 0;

        foreach($items as $item){
            if(!empty($item)){
                $priceStr = str_replace('.','', $prices[$idx]);
                $price = (double) $priceStr;
                $qty = (double) $qtys[$idx];
                $invDetail = PurchaseInvoiceDetail::create([
                    'header_id'     => $invHeader->id,
                    'item_id'       => $item,
                    'quantity'      => $qty,
                    'price'         => $priceStr
                ]);

                // Check discount
                if(!empty($discounts[$idx]) && $discounts[$idx] !== '0'){
                    $invDetail->discount = $discounts[$idx];

                    $discount = (double) $discounts[$idx];
                    $discountAmount = ($qty * $price) * $discount / 100;
                    $invDetail->subtotal = ($qty * $price) - $discountAmount;

                    // Accumulate total price
                    $totalPrice += $qty * $price;

                    // Accumulate total discount
                    $totalDiscount += $discountAmount;
                }
                else{
                    $invDetail->subtotal = $qty * $price;
                    $totalPrice += $qty * $price;
                }

                if(!empty($remarks[$idx])) $invDetail->remark = $remarks[$idx];
                $invDetail->save();

                // Accumulate subtotal
                $totalPayment += $invDetail->subtotal;
            }
            $idx++;
        }

        if($totalDiscount > 0) $invHeader->total_discount = $totalDiscount;
        $totalPayment += $delivery;
        $invHeader->total_price = $totalPrice;

        // Save total payment without tax
        $invHeader->total_payment_before_tax = $totalPayment;

        // Get PPN & PPh
        $ppnAmount = 0;
        if($request->filled('ppn') && $request->input('ppn') != '0'){
            $ppnAmount = $totalPayment * (10 / 100);
            $invHeader->ppn_percent = 10;
            $invHeader->ppn_amount = $ppnAmount;
        }
        $pphAmount = 0;
        if($request->filled('pph') && $request->input('pph') != '0'){
            $pph = str_replace('.','', $request->input('pph'));
            $pphAmount = (double) $pph;
            $invHeader->pph_amount = $pphAmount;
        }

        $invHeader->total_payment = $totalPayment + $ppnAmount - $pphAmount;
        $invHeader->save();

        Session::flash('message', 'Berhasil membuat purchase invoice!');

        return redirect()->route('admin.purchase_invoices.show', ['purchase_invoice' => $invHeader]);
    }

    public function edit(PurchaseInvoiceHeader $purchase_invoice){
        $header = $purchase_invoice;
        $date = Carbon::parse($purchase_invoice->date)->format('d M Y');

        $data = [
            'header'    => $header,
            'date'      => $date
        ];

        return View('admin.purchasing.purchase_invoices.edit')->with($data);
    }

    public function update(Request $request, PurchaseInvoiceHeader $purchase_invoice){
        $validator = Validator::make($request->all(),[
            'date'      => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        if($request->filled('po_code')) $purchase_invoice->purchase_order_id = $request->input('po_code');

        $date = Carbon::createFromFormat('d M Y', $request->input('date'), 'Asia/Jakarta');
        $purchase_invoice->date = $date->toDateTimeString();

        $totalPaymentWithoutTax = $purchase_invoice->total_payment_before_tax;

        $oldDelivery = $purchase_invoice->delivery_fee ?? 0;
        $newDelivery = 0;
        if($request->filled('delivery_fee') && $request->input('delivery_fee') != '0'){
            $deliveryFee = str_replace('.','', $request->input('delivery_fee'));
            $newDelivery = (double) $deliveryFee;
            $purchase_invoice->delivery_fee = $deliveryFee;
        }
        else{
            $purchase_invoice->delivery_fee = null;
        }
        $totalPayment = $totalPaymentWithoutTax - $oldDelivery + $newDelivery;
        $purchase_invoice->total_payment_before_tax = $totalPayment;

        // Get PPN & PPh
        $ppnAmount = 0;
        if($request->filled('ppn')){
            $ppnAmount = $totalPayment * (10 / 100);
            $purchase_invoice->ppn_percent = 10;
            $purchase_invoice->ppn_amount = $ppnAmount;
        }
        else{
            $purchase_invoice->ppn_percent = null;
            $purchase_invoice->ppn_amount = null;
        }

        $pphAmount = 0;
        if($request->filled('pph')){
            $pph = str_replace('.','', $request->input('pph'));
            $pphAmount = (double) $pph;
            $purchase_invoice->pph_amount = $pphAmount;
        }
        else{
            $purchase_invoice->pph_percent = null;
            $purchase_invoice->pph_amount = null;
        }

        $purchase_invoice->total_payment = $totalPayment + $ppnAmount - $pphAmount;
        $purchase_invoice->save();

        Session::flash('message', 'Berhasil ubah purchase invoice!');

        return redirect()->route('admin.purchase_invoices.show', ['purchase_invoice' => $purchase_invoice]);
    }

//    public function report(){
//        return View('admin.purchasing.purchase_orders.report');
//    }
//
//    public function downloadReport(Request $request) {
//        //Get Data First
//        $tempStart = strtotime(Input::get('start_date'));
//        $start = date('Y-m-d', $tempStart);
//        $tempEnd = strtotime(Input::get('end_date'));
//        $end = date('Y-m-d', $tempEnd);
//
//        //Check date
//        if($start > $end){
//            return redirect()->back()->withErrors('Start Date Tidak boleh lebih besar dari Finish Date!', 'default')->withInput($request->all());
//        }
//
//        $data = PurchaseOrderHeader::whereBetween('created_at', array($start, $end))->get();
//
//        //Check Data
//        if($data == null || $data->count() == 0){
//            return redirect()->back()->withErrors('Data Tidak Ditemukan!', 'default')->withInput($request->all());
//        }
//
//        $total = 0;
//        foreach ($data as $item){
//            $total += $item->total_payment;
//        }
//        $totalStr = 'Rp '. number_format($total, 0, ",", ".");
//
//        $pdf = PDF::loadView('documents.purchase_orders.purchase_orders_pdf', ['data' => $data, 'start_date' => Input::get('start_date'), 'finish_date' => Input::get('end_date'), 'total' => $totalStr])
//            ->setPaper('a4', 'landscape');
//        $now = Carbon::now('Asia/Jakarta');
//        $filename = 'PURCHASE_ORDER_REPORT_' . $now->toDateTimeString();
//
//        return $pdf->download($filename.'.pdf');
//    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getIndex(Request $request){
        try{
            $mode = 'default';
            if($request->filled('mode')){
                $mode = $request->input('mode');
            }

            $purchaseOrders = PurchaseInvoiceHeader::dateDescending()->get();
            return DataTables::of($purchaseOrders)
                ->setTransformer(new PurchaseInvoiceHeaderTransformer($mode))
                ->addIndexColumn()
                ->make(true);
        }
        catch(\Exception $ex){
            error_log($ex);
        }
    }

    public function getPurchaseInvoices(Request $request){
        $term = trim($request->q);
        $purchase_invoices = PurchaseInvoiceHeader::where('code', 'LIKE', '%'. $term. '%')
            ->get();

        $formatted_tags = [];

        foreach ($purchase_invoices as $purchase_invoice) {
            $formatted_tags[] = ['id' => $purchase_invoice->id, 'text' => $purchase_invoice->code];
        }

        return \Response::json($formatted_tags);
    }
}