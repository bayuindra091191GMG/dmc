<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 28/02/2018
 * Time: 11:02
 */

namespace App\Http\Controllers\Admin\Purchasing;


use App\Http\Controllers\Controller;
use App\Models\PurchaseOrderDetail;
use App\Models\PurchaseOrderHeader;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class PurchaseOrderDetailController extends Controller
{
    public function store(Request $request){
        try{
            $validator = Validator::make($request->all(),[
                'item'      => 'required',
                'qty'       => 'required',
                'price'     => 'required',
                'remark'    => 'max:200'
            ]);

            if ($validator->fails()) {
                return Response::json(array('errors' => $validator->getMessageBag()->toArray()));
            }

            $detail = new PurchaseOrderDetail();
            $detail->header_id = $request->input('header_id');
            $detail->item_id = $request->input('item');

            $qty = (double) $request->input('qty');
            $detail->quantity = $qty;

            $priceStr = str_replace('.','', $request->input('price'));
            $price = (double) $priceStr;
            $detail->price = $price;

            // Check discount and subtotal
            $finalSubtotal = 0;
            $discountAmount = 0;
            if($request->filled('discount')){
                $discount = (double) $request->input('discount');
                $detail->discount = $discount;
                $discountAmount = ($qty * $price) * $discount/ 100;
                $finalSubtotal = ($qty * $price) - $discountAmount;
                $detail->subtotal = $finalSubtotal;
            }
            else{
                $finalSubtotal = ($qty * $price);
                $detail->subtotal = $finalSubtotal;
            }

            if(!empty(Input::get('remark'))) $detail->remark = Input::get('remark');

            $detail->save();

            // Accumulate total price, discount & payment
            $header = PurchaseOrderHeader::find($request->input('header_id'));
            $totalPrice = $header->total_price + ($qty * $price);
            $header->total_price = $totalPrice;

//            $deliveryFee = $header->delivery_fee ?? 0;
            $totalPayment = $header->total_payment_before_tax + $finalSubtotal;
            $header->total_payment_before_tax = $totalPayment;

            // Get PPN & PPh
            $ppnAmount = 0;
            if(!empty($header->ppn_percent) && $header->ppn_percent > 0){
                $ppnAmount = $totalPayment * (10 / 100);
                $header->ppn_percent = 10;
                $header->ppn_amount = $ppnAmount;
            }
            else{
                $header->ppn_percent = null;
                $header->ppn_amount = null;
            }

            $pphAmount = 0;
            if(!empty($header->pph_amount) && $header->pph_amount > 0){
                $pphAmount = $header->pph_amount;
            }
            else{
                $header->pph_percent = null;
                $header->pph_amount = null;
            }

            $header->total_payment = $totalPayment + $ppnAmount - $pphAmount;
            if($request->filled('discount')){
                $header->total_discount += $discountAmount;
            }
            $header->save();

            $json = PurchaseOrderDetail::with('item')->find($detail->id);
            return new JsonResponse($json);
        }
        catch (\Exception $ex){
            error_log($ex);
        }
    }

    public function update(Request $request){
        try{
            $validator = Validator::make($request->all(),[
                'qty'       => 'required',
                'price'     => 'required',
                'remark'    => 'max:200'
            ]);

            if ($validator->fails()) {
                return Response::json(array('errors' => $validator->getMessageBag()->toArray()));
            }

            $detail = PurchaseOrderDetail::find($request->input('id'));

            // Get old value
            $oldPrice = $detail->price;
            $oldDiscountAmount = 0;
            $oldSubtotal = $detail->subtotal;
            $oldQty = $detail->quantity;

            if($request->filled('item')){
                $detail->item_id = $request->input('item');
            }

            $qty = (double) $request->input('qty');

            $detail->quantity = $qty;
            $priceStr = str_replace('.','', $request->input('price'));
            $price = (double) $priceStr;
            $detail->price = $price;

            // Check discount and subtotal
            $finalSubtotal = 0;
            $discountAmount = 0;
            if($request->filled('discount')){
                // Get old discount
                $oldDiscountAmount = ($oldQty * $oldPrice) * $detail->discount / 100;

                $discount = (double) $request->input('discount');
                $detail->discount = $discount;
                $discountAmount = ($qty * $price) * $discount/ 100;
                $finalSubtotal = ($qty * $price) - $discountAmount;
                $detail->subtotal = $finalSubtotal;
            }
            else{
                $finalSubtotal = ($qty * $price);
                $detail->subtotal = $finalSubtotal;
            }

            if($request->filled('remark')) $detail->remark = $request->input('remark');

            $detail->save();

            // Accumulate total price, discount & payment
            $header = PurchaseOrderHeader::find($detail->header_id);
            $totalPrice = $header->total_price - ($oldQty * $oldPrice) + ($qty * $price);
            $header->total_price = $totalPrice;

//            $deliveryFee = $header->delivery_fee ?? 0;
            $totalPayment = $header->total_payment_before_tax - $oldSubtotal + $finalSubtotal;
            $header->total_payment_before_tax = $totalPayment;

            if($request->filled('discount')){
                $header->total_discount = $header->total_discount - $oldDiscountAmount + $discountAmount;
            }

            // Get PPN & PPh
            $ppnAmount = 0;
            if(!empty($header->ppn_percent) && $header->ppn_percent > 0){
                $ppnAmount = $totalPayment * (10 / 100);
                $header->ppn_percent = 10;
                $header->ppn_amount = $ppnAmount;
            }
            else{
                $header->ppn_percent = null;
                $header->ppn_amount = null;
            }

            $pphAmount = 0;
            if(!empty($header->pph_amount) && $header->pph_amount > 0){
                $pphAmount = $header->pph_amount;
            }
            else{
                $header->pph_percent = null;
                $header->pph_amount = null;
            }

            $header->total_payment = $totalPayment + $ppnAmount - $pphAmount;
            $header->save();

            $json = PurchaseOrderDetail::with('item')->find($detail->id);
            return new JsonResponse($json);
        }
        catch(\Exception $ex){
            error_log($ex);
        }
    }

    public function delete(Request $request){
        try{

            $details = PurchaseOrderDetail::where('header_id', Input::get('header_id'))->get();
            if($details->count() == 1){
                return Response::json(array('errors' => 'INVALID'));
            }

            $detail = PurchaseOrderDetail::find(Input::get('id'));

            // Get old value
            $oldPrice = $detail->price;
            $oldDiscountAmount = 0;
            $oldSubtotal = $detail->subtotal;
            $oldQty = $detail->quantity;

            $oldSubtotal = 0;
            if(!empty($detail->discount)){
                $oldDiscountAmount = ($oldQty * $oldPrice) * $detail->discount / 100;
                $oldSubtotal = ($oldQty * $oldPrice) - $oldDiscountAmount;
            }
            else{
                $oldSubtotal = ($oldQty * $oldPrice);
            }

            // Minus header total values
            $header = PurchaseOrderHeader::find($detail->header_id);
            $header->total_price = $header->total_price - ($oldQty * $oldPrice);

//            $deliveryFee = $header->delivery_fee ?? 0;
            $totalPayment = $header->total_payment_before_tax - $oldSubtotal;
            $header->total_payment_before_tax = $totalPayment;

            $header->total_discount = $header->total_discount -  $oldDiscountAmount;

            // Get PPN & PPh
            $ppnAmount = 0;
            if(!empty($header->ppn_percent) && $header->ppn_percent > 0){
                $ppnAmount = $totalPayment * (10 / 100);
                $header->ppn_percent = 10;
                $header->ppn_amount = $ppnAmount;
            }
            else{
                $header->ppn_percent = null;
                $header->ppn_amount = null;
            }

            $pphAmount = 0;
            if(!empty($header->pph_amount) && $header->pph_amount > 0){
                $pphAmount = $header->pph_amount;
            }
            else{
                $header->pph_percent = null;
                $header->pph_amount = null;
            }

            $header->total_payment = $totalPayment + $ppnAmount - $pphAmount;

            $now = Carbon::now('Asia/Jakarta');
            $header->updated_at = $now->toDateTimeString();
            $header->save();

            // Delete purchase order detail completely
            $detail->delete();

            return new JsonResponse($detail);
        }
        catch (\Exception $ex){
            error_log($ex);
        }
    }
}