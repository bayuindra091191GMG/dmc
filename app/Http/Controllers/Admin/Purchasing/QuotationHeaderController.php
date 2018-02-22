<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 19/02/2018
 * Time: 10:25
 */

namespace App\Http\Controllers\Admin\Purchasing;


use App\Http\Controllers\Controller;
use App\Models\QuotationDetail;
use App\Models\QuotationHeader;
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

    public function create(){
        return View('admin.purchasing.quotations.create');
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'quot_code'     => 'required|max:40',
            'pr_code'       => 'required',
            'supplier'      => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check detail
        $items = Input::get('item');
        $valid = false;
        foreach($items as $item){
            if(!empty($item)) $valid = true;
        }

        if(!$valid){
            return redirect()->back()->withErrors('Daftar barang wajib diisi!', 'default')->withInput($request->all());
        }

        $user = \Auth::user();
        $now = Carbon::now('Asia/Jakarta');

        $quotHeader = QuotationHeader::create([
            'code'      => Input::get('quot_code'),
            'purchase_request_id'   => Input::get('pr_code'),
            'supplier_id'           => Input::get('supplier'),
            'status_id'             => 3,
            'created_by'            => $user->id,
            'created_at'            => $now->toDateTimeString()
        ]);

        // Create quotation detail
        $totalPrice = 0;
        $totalDiscount = 0;
        $totalPayment = 0;
        $qtys = Input::get('qty');
        $prices = Input::get('price');
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
        $quotHeader->total_payment = $totalPayment;
        $quotHeader->save();

        Session::flash('message', 'Berhasil membuat quotation vendor!');

        return redirect()->route('admin.quotations.show', ['quotation' => $quotHeader]);
    }

    public function edit(QuotationHeader $quotation){
        $header = $quotation;

        return View('admin.purchasing.quotations.edit', compact('header'));
    }

    public function update(Request $request, QuotationHeader $quotation){
        if(!empty(Input::get('pr_code'))) $quotation->purchase_request_id = Input::get('pr_code');
        if(!empty(Input::get('supplier'))) $quotation->supplier_id = Input::get('supplier');
        $quotation->save();

        Session::flash('message', 'Berhasil ubah quotation vendor!');

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
}