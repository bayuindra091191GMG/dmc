<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 19/02/2018
 * Time: 10:25
 */

namespace App\Http\Controllers\Admin\Purchasing;


use App\Http\Controllers\Controller;
use App\Libs\Utilities;
use App\Models\NumberingSystem;
use App\Models\PurchaseRequestHeader;
use App\Models\QuotationDetail;
use App\Models\QuotationHeader;
use App\Models\Supplier;
use App\Transformer\Purchasing\QuotationHeaderTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class QuotationHeaderController extends Controller
{
    public function index(){
        return View('admin.purchasing.quotations.index');
    }

    public function show(QuotationHeader $quotation){
        $header = $quotation;

        return View('admin.purchasing.quotations.show', compact('header'));
    }

    public function beforeCreate(){
        return View('admin.purchasing.quotations.before_create');
    }

    public function beforeCreateEmpty(){
        return View('admin.purchasing.quotations.before_create_empty');
    }

    public function create(){
        if(empty(request()->pr)){
            return redirect()->route('admin.quotations.before_create');
        }

        $purchaseRequest = PurchaseRequestHeader::find(request()->pr);

        // Numbering System
        $sysNo = NumberingSystem::where('doc_id', '5')->first();
        $autoNumber = Utilities::GenerateNumber($sysNo->document->code, $sysNo->next_no);

        $data = [
            'purchaseRequest'   => $purchaseRequest,
            'autoNumber'        => $autoNumber
        ];

        return View('admin.purchasing.quotations.create')->with($data);
    }

    public function createEmpty(){
        if(empty(request()->pr)){
            return redirect()->route('admin.quotations.before_create_empty');
        }

        $purchaseRequest = PurchaseRequestHeader::find(request()->pr);

        // Numbering System
//        $sysNo = NumberingSystem::where('doc_id', '5')->first();
//        $autoNumber = Utilities::GenerateNumber($sysNo->document->code, $sysNo->next_no);

        $data = [
            'purchaseRequest'   => $purchaseRequest
        ];

        return View('admin.purchasing.quotations.create_empty')->with($data);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'quot_code'     => 'required|max:40',
            'pr_code'       => 'required'
        ],[
            'quot_code.required'    => 'Nomor RFQ wajib diisi!'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Validate quotation number
        if(empty(Input::get('auto_number')) && (empty(Input::get('quot_code')) || Input::get('quot_code') == "")){
            return redirect()->back()->withErrors('Nomor kuotasi vendor wajib diisi!', 'default')->withInput($request->all());
        }

        // Validate details
        $items = $request->input('item_value');

        if(count($items) == 0){
            return redirect()->back()->withErrors('Detail inventory wajib diisi!', 'default')->withInput($request->all());
        }

        $qtys = $request->input('qty');
        $prices = $request->input('price');
        $valid = true;
        $i = 0;
        foreach($items as $item){
            if(empty($item)) $valid = false;
            if(empty($qtys[$i]) || $qtys[$i] == '0') $valid = false;
            $i++;
        }

        if(!$valid){
            return redirect()->back()->withErrors('Detail inventory & kuantitas wajib diisi!', 'default')->withInput($request->all());
        }

        // Check duplicate inventory
        $valid = Utilities::arrayIsUnique($items);
        if(!$valid){
            return redirect()->back()->withErrors('Detail inventory tidak boleh kembar!', 'default')->withInput($request->all());
        }

        // Generate auto number
        $quotCode = 'default';
        if(Input::get('auto_number')){
            $sysNo = NumberingSystem::where('doc_id', '5')->first();
            $quotCode = Utilities::GenerateNumberPurchaseOrder($sysNo->document->code, $sysNo->next_no);
            $sysNo->next_no++;
            $sysNo->save();
        }
        else{
            $quotCode = Input::get('quot_code');
        }

        // Check existing number
        if(QuotationHeader::where('code', $quotCode)->exists()){
            return redirect()->back()->withErrors('Nomor kuotasi vendor sudah terdaftar!', 'default')->withInput($request->all());
        }

        $user = \Auth::user();
        $now = Carbon::now('Asia/Jakarta');

        $quotHeader = QuotationHeader::create([
            'code'                  => $quotCode,
            'purchase_request_id'   => Input::get('pr_id'),
            'supplier_id'           => Input::get('supplier'),
            'status_id'             => 3,
            'created_by'            => $user->id,
            'created_at'            => $now->toDateTimeString()
        ]);

        $delivery = 0;
        if($request->filled('delivery_fee') && $request->input('delivery_fee') != '0'){
            $deliveryFee = str_replace('.','', $request->input('delivery_fee'));
            $delivery = (double) $deliveryFee;
            $quotHeader->delivery_fee = $deliveryFee;
        }

        $date = Carbon::createFromFormat('d M Y', $request->input('date'), 'Asia/Jakarta');
        $quotHeader->date = $date->toDateTimeString();

        $quotHeader->save();

        // Create quotation detail
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
                $quotDetail = QuotationDetail::create([
                    'header_id'     => $quotHeader->id,
                    'item_id'       => $item,
                    'quantity'      => $qty,
                    'price'         => $priceStr
                ]);

                // Check discount
                if(!empty($discounts[$idx]) && $discounts[$idx] !== '0'){
                    $quotDetail->discount = $discounts[$idx];

                    $discount = (double) $discounts[$idx];
                    $discountAmount = ($qty * $price) * $discount / 100;
                    $quotDetail->subtotal = ($qty * $price) - $discountAmount;

                    // Accumulate total price
                    $totalPrice += $qty * $price;

                    // Accumulate total discount
                    $totalDiscount += $discountAmount;
                }
                else{
                    $quotDetail->subtotal = $qty * $price;
                    $totalPrice += $qty * $price;
                }

                if(!empty($remarks[$idx])) $quotDetail->remark = $remarks[$idx];
                $quotDetail->save();

                // Accumulate subtotal
                $totalPayment += $quotDetail->subtotal;
            }
            $idx++;
        }

        if($totalDiscount > 0) $quotHeader->total_discount = $totalDiscount;
        $quotHeader->total_price = $totalPrice;

        // Save total payment without tax
        $quotHeader->total_payment_before_tax = $totalPayment;

        // Get PPN & PPh
        $ppnAmount = 0;
        if($request->filled('ppn') && $request->input('ppn') != '0'){
            $ppnAmount = $totalPayment * (10 / 100);
            $quotHeader->ppn_percent = 10;
            $quotHeader->ppn_amount = $ppnAmount;
        }
        $pphAmount = 0;
        if($request->filled('pph') && $request->input('pph') != '0'){
            $pph = str_replace('.','', $request->input('pph'));
            $pphAmount = (double) $pph;
            $quotHeader->pph_amount = $pphAmount;
        }

        $quotHeader->total_payment = $totalPayment + $delivery + $ppnAmount - $pphAmount;
        $quotHeader->save();

        Session::flash('message', 'Berhasil membuat RFQ vendor!');

        return redirect()->route('admin.quotations.show', ['quotation' => $quotHeader]);
    }

    public function printEmpty(Request $request){
        $validator = Validator::make($request->all(),[
            'pr_code'       => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Validate quotation number
//        if(empty(Input::get('auto_number')) && (empty(Input::get('quot_code')) || Input::get('quot_code') == "")){
//            return redirect()->back()->withErrors('Nomor kuotasi vendor wajib diisi!', 'default')->withInput($request->all());
//        }

        // Validate details
        $items = $request->input('item');

        if(count($items) == 0){
            return redirect()->back()->withErrors('Detail inventory wajib diisi!', 'default')->withInput($request->all());
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
            return redirect()->back()->withErrors('Detail inventory & kuantitas wajib diisi!', 'default')->withInput($request->all());
        }

        // Check duplicate inventory
        $valid = Utilities::arrayIsUnique($items);
        if(!$valid){
            return redirect()->back()->withErrors('Detail inventory tidak boleh kembar!', 'default')->withInput($request->all());
        }

        // Generate auto number
//        $quotCode = 'default';
//        if(Input::get('auto_number')){
//            $sysNo = NumberingSystem::where('doc_id', '5')->first();
//            $quotCode = Utilities::GenerateNumberPurchaseOrder($sysNo->document->code, $sysNo->next_no);
//            $sysNo->next_no++;
//            $sysNo->save();
//        }
//        else{
//            $quotCode = Input::get('quot_code');
//        }
//
//        // Check existing number
//        if(QuotationHeader::where('code', $quotCode)->exists()){
//            return redirect()->back()->withErrors('Nomor kuotasi vendor sudah terdaftar!', 'default')->withInput($request->all());
//        }

        $user = \Auth::user();
        $now = Carbon::now('Asia/Jakarta');

        $supplier = Supplier::find($request->input('supplier'));

        $quotHeader = new QuotationHeader();
        $quotHeader->purchase_request_id = $request->input('pr_id');
        $quotHeader->supplier_id = $request->input('supplier');
        $quotHeader->supplier = $supplier;
        $quotHeader->status_id = 3;
        $quotHeader->created_by = $user->id;
        $quotHeader->created_at = $now->toDateTimeString();

        $date = Carbon::createFromFormat('d M Y', $request->input('date'), 'Asia/Jakarta');
        $quotHeader->date = $date->toDateTimeString();

        // Create quotation detail
        $totalPrice = 0;
        $totalDiscount = 0;
        $totalPayment = 0;
        $discounts = Input::get('discount');
        $remarks = Input::get('remark');
        $idx = 0;
        foreach($items as $item){
            if(!empty($item)){
                $qty = (double) $qtys[$idx];
                $quotDetail = new QuotationDetail();
                $quotDetail->header_id = 99;
                $quotDetail->item_id = $item;
                $quotDetail->quantity = $qty;

                if(!empty($remarks[$idx])) $quotDetail->remark = $remarks[$idx];

                $quotHeader->quotation_details->add($quotDetail);

                // Accumulate subtotal
                $totalPayment += $quotDetail->subtotal;
            }
            $idx++;
        }

        $data = [
            'quotHeader'    => $quotHeader,
            'now'           => $now->toDateTimeString()
        ];

        return View('documents.quotations.quotations_doc')->with($data);
    }


    public function edit(QuotationHeader $quotation){
        $header = $quotation;

        return View('admin.purchasing.quotations.edit', compact('header'));
    }

    public function update(Request $request, QuotationHeader $quotation){
        $validator = Validator::make($request->all(),[
            'date'          => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        if($request->filled('supplier')) $quotation->supplier_id = $request->input('supplier');


        $date = Carbon::createFromFormat('d M Y', $request->input('date'), 'Asia/Jakarta');
        $quotation->date = $date->toDateTimeString();

        $totalPaymentWithoutTax = $quotation->total_payment_before_tax;

        $oldDelivery = $quotation->delivery_fee ?? 0;
        $newDelivery = 0;
        if($request->filled('delivery_fee') && $request->input('delivery_fee') != '0'){
            $deliveryFee = str_replace('.','', $request->input('delivery_fee'));
            $newDelivery = (double) $deliveryFee;
            $quotation->delivery_fee = $deliveryFee;
        }
        else{
            $quotation->delivery_fee = null;
        }
        $totalPayment = $totalPaymentWithoutTax;
        $quotation->total_payment_before_tax = $totalPayment;

        // Get PPN & PPh
        $ppnAmount = 0;
        if($request->filled('ppn')){
            $ppnAmount = $totalPayment * (10 / 100);
            $quotation->ppn_percent = 10;
            $quotation->ppn_amount = $ppnAmount;
        }
        else{
            $quotation->ppn_percent = null;
            $quotation->ppn_amount = null;
        }

        $pphAmount = 0;
        if($request->filled('pph')){
            $pph = str_replace('.','', $request->input('pph'));
            $pphAmount = (double) $pph;
            $quotation->pph_amount = $pphAmount;
        }
        else{
            $quotation->pph_percent = null;
            $quotation->pph_amount = null;
        }

        $quotation->total_payment = $totalPayment - $oldDelivery + $newDelivery + $ppnAmount - $pphAmount;
        $quotation->save();

        Session::flash('message', 'Berhasil ubah RFQ vendor!');

        return redirect()->route('admin.quotations.edit', ['quotation' => $quotation]);
    }

    public function getIndex(){
        try{
            $quotationHeaders = QuotationHeader::all();
            return DataTables::of($quotationHeaders)
                ->setTransformer(new QuotationHeaderTransformer)
                ->addIndexColumn()
                ->make(true);
        }
        catch(\Exception $ex){
            error_log($ex);
        }
    }

    public function print(QuotationHeader $quotation){
        $quotHeader = $quotation;
        return view('documents.quotations.quotations_doc', compact('quotHeader'));
    }
}