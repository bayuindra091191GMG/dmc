<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 12/07/2018
 * Time: 11:26
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\TransactionDetail;
use App\Models\TransactionHeader;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class TransactionProrateDetailController extends Controller
{
    public function store(Request $request){
        try{
            $validator = Validator::make($request->all(),[
                'schedule'      => 'required'
            ]);

            if ($validator->fails()) {
                return Response::json(array('errors' => $validator->getMessageBag()->toArray()));
            }

            $now = Carbon::now('Asia/Jakarta');
            $user = Auth::user();

            $detail = new TransactionDetail();
            $detail->header_id = $request->input('header_id');
            $detail->updated_by = $user->id;
            $detail->updated_at = $now->toDateTimeString();

            // Filter schedule
            $scheduleInput = $request->input('schedule');
            $scheduleArr = explode('#', $scheduleInput);

            // Check duplicated schedule
            $header = $detail->transaction_header;
            foreach($header->transaction_details as $tmpDetail){
                if($tmpDetail->schedule_id == $scheduleArr[0]){
                    return Response::json(array('errors' => 'EXISTS'));
                }
            }

            $schedule = Schedule::find($scheduleArr[0]);

            $proratePriceStr = str_replace('.','', $request->input('prorate_price'));
            $proratePrice = (double) $proratePriceStr;

            $detail->schedule_id = $scheduleArr[0];
            $detail->prorate = $request->input('prorate');
            $detail->price = $schedule->course->price;
            $detail->prorate_price = $proratePrice;
            $normalPrice = $schedule->course->price;

            // Check discount and subtotal
            $discount = 0;
            if($request->filled('discount')){
                $discountStr = str_replace('.','', $request->input('discount'));
                $discount = (double) $discountStr;
                $detail->discount = $discount;
                $finalSubtotal = $proratePrice - $discount;
                $detail->subtotal = $finalSubtotal;
            }
            else{
                $finalSubtotal = $proratePrice;
                $detail->subtotal = $finalSubtotal;
            }

            $detail->save();

            // Accumulate total price, discount & payment
            $header->total_price += $normalPrice;
            $header->total_prorate_price += $proratePrice;

            $header->total_payment += $finalSubtotal;
            if($request->filled('discount')){
                $header->total_discount += $discount;
            }
            $header->save();

            return new JsonResponse($detail);
        }
        catch (\Exception $ex){
            error_log($ex);
            return Response::json(array('errors' => 'INVALID'));
        }
    }

    public function update(Request $request){
        try{
            $now = Carbon::now('Asia/Jakarta');
            $user = Auth::user();

            $detail = TransactionDetail::find($request->input('id'));
            $detail->updated_by = $user->id;
            $detail->updated_at = $now->toDateTimeString();

            $proratePriceStr = str_replace('.','', $request->input('prorate_price'));
            $proratePrice = (double) $proratePriceStr;

            // Get old value
            $oldPrice = $detail->price;
            $oldProratePrice = $detail->prorate_price;
            $oldDiscountAmount =  $detail->discount ?? 0;

            $detail->prorate = $request->input('prorate');
            $detail->prorate_price =  $proratePrice;

            $price = 0;
            if($request->filled('schedule')){
                // Filter schedule
                $scheduleInput = $request->input('schedule');
                $scheduleArr = explode('#', $scheduleInput);

                // Check duplicated schedule
                $headerTmp = $detail->transaction_header;
                foreach($headerTmp->transaction_details as $tmpDetail){
                    if($tmpDetail->schedule_id == $scheduleArr[0]){
                        return Response::json(array('errors' => 'EXISTS'));
                    }
                }

                $schedule = Schedule::find($scheduleArr[0]);
                $price = $schedule->course->price;

                $detail->schedule_id = $scheduleArr[0];
                $detail->price = $price;
            }
            else{
                $price = $oldPrice;
            }

            // Check discount and subtotal
            $finalSubtotal = 0;
            $discountAmount = 0;
            if($request->filled('discount') && $request->input('discount') != '0'){
                $discountStr = str_replace('.','', $request->input('discount'));
                $discountAmount = (double) $discountStr;
                $detail->discount = $discountAmount;
                $finalSubtotal = $proratePrice - $discountAmount;
                $detail->subtotal = $finalSubtotal;
            }
            else{
                $detail->discount = 0;
                $finalSubtotal = $proratePrice;
                $detail->subtotal = $finalSubtotal;
            }

            $detail->save();

            // Accumulate total price, discount & payment
            $header = TransactionHeader::find($detail->header_id);

            $totalPrice = $header->total_price - $oldPrice + $price;
            $header->total_price = $totalPrice;

            $totalProratePrice = $header->total_prorate_price - $oldProratePrice + $proratePrice;
            $header->total_prorate_price = $totalProratePrice;

            $header->total_discount = $header->total_discount - $oldDiscountAmount + $discountAmount;
            $header->total_payment = $header->registration_fee + $totalProratePrice - $header->total_discount;

            $header->updated_by = $user->id;
            $header->updated_at = $now->toDateTimeString();
            $header->save();

            return new JsonResponse($detail);
        }
        catch(\Exception $ex){
            error_log($ex);
            return Response::json(array('errors' => 'EXCEPTION'));
        }
    }

    public function delete(Request $request){
        try{

            $details = TransactionDetail::where('header_id', $request->input('header_id'))->get();
            if($details->count() == 1){
                return Response::json(array('errors' => 'INVALID'));
            }

            $detail = TransactionDetail::find($request->input('detail_id'));

            // Get old value
            $oldPrice = $detail->price;
            $oldProratePrice = $detail->prorate_price;
            $oldDiscountAmount = $detail->discount ?? 0;
            $oldSubtotal = $detail->subtotal;

            // Minus header total values
            $header = $detail->transaction_header;
            $header->total_price -= $oldPrice;
            $header->total_prorate_price -= $oldProratePrice;
            $header->total_discount -= $oldDiscountAmount;
            $header->total_payment -= $oldSubtotal;

            $now = Carbon::now('Asia/Jakarta');
            $user = Auth::user();
            $header->updated_by = $user->id;
            $header->updated_at = $now->toDateTimeString();
            $header->save();

            // Delete transaction detail completely
            $detail->delete();

            return new JsonResponse($detail);
        }
        catch (\Exception $ex){
            error_log($ex);
        }
    }
}