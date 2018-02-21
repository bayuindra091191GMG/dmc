<?php
/**
 * Created by PhpStorm.
 * User: hellb
 * Date: 2/20/2018
 * Time: 3:45 PM
 */

namespace App\Http\Controllers\Admin\Purchasing;


use App\Http\Controllers\Controller;
use App\Models\QuotationDetail;
use App\Models\QuotationHeader;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class QuotationDetailController extends Controller
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

            $detail = new QuotationDetail();
            $detail->header_id = Input::get('header_id');
            $detail->item_id = Input::get('item');
            $detail->quantity = Input::get('qty');

            $priceStr = str_replace('.','', Input::get('price'));
            $price = (double) $priceStr;
            $detail->price = $price;

            // Check discount and subtotal
            $finalSubtotal = 0;
            $discountAmount = 0;
            if(!empty(Input::get('discount'))){
                $discount = (double) Input::get('discount');
                $detail->discount = $discount;
                $discountAmount = $price * $discount/ 100;
                $finalSubtotal = $price - $discountAmount;
                $detail->subtotal = $price - $finalSubtotal;
            }
            else{
                $finalSubtotal = $price;
                $detail->subtotal = $finalSubtotal;
            }

            if(!empty(Input::get('remark'))) $detail->remark = Input::get('remark');

            $detail->save();

            // Accumulate total price, discount & payment
            $header = QuotationHeader::find(Input::get('header_id'));
            $header->total_price = $header->total_price + $price;
            $header->total_payment = $header->total_payment + $finalSubtotal;
            if(!empty(Input::get('discount'))){
                $header->total_discount += $discountAmount;
            }
            $header->save();

            $json = QuotationDetail::with('item')->find($detail->id);
            return new JsonResponse($json);
        }
        catch (\Exception $ex){
            error_log($ex);
        }
    }

    public function update(Request $request){
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

            $detail = QuotationDetail::find(Input::get('id'));

            // Get old value
            $oldPrice = $detail->price;
            $oldDiscountAmount = 0;
            $oldSubtotal = $detail->subtotal;

            $detail->item_id = Input::get('item');
            $detail->quantity = Input::get('qty');
            $priceStr = str_replace('.','', Input::get('price'));
            $price = doubleval($priceStr);
            $detail->price = $price;

            // Check discount and subtotal
            $finalSubtotal = 0;
            $discountAmount = 0;
            if(!empty(Input::get('discount'))){
                // Get old discount
                $oldDiscountAmount = $oldPrice * $detail->discount / 100;

                $discount = floatval(Input::get('discount'));
                $detail->discount = $discount;
                $discountAmount = $price * $discount/ 100;
                $finalSubtotal = $price - $discountAmount;
                $detail->subtotal = $price - $finalSubtotal;
            }
            else{
                $finalSubtotal = $price;
                $detail->subtotal = $finalSubtotal;
            }

            if(!empty(Input::get('remark'))) $detail->remark = Input::get('remark');

            $detail->save();

            // Accumulate total price, discount & payment
            $header = QuotationHeader::find($detail->header_id);
            $header->total_price = $header->total_price - $oldPrice + $price;
            $header->total_payment = $header->total_payment - $oldSubtotal + $finalSubtotal;
            if(!empty(Input::get('discount'))){
                $header->total_discount = $header->total_discount - $oldDiscountAmount + $discountAmount;
            }
            $header->save();

            $json = QuotationDetail::with('item')->find($detail->id);
            return new JsonResponse($json);
        }
        catch(\Exception $ex){
            error_log($ex);
        }
    }

    public function delete(Request $request){
        try{
            $detail = QuotationDetail::find(Input::get('id'));
            $detail->delete();

            return new JsonResponse($detail);
        }
        catch (\Exception $ex){
            error_log($ex);
        }
    }
}