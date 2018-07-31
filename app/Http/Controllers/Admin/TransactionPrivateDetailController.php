<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 30/07/2018
 * Time: 13:42
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Schedule;
use App\Models\TransactionDetail;
use App\Models\TransactionHeader;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class TransactionPrivateDetailController extends Controller
{
    public function store(Request $request){
        try{
            $meeting = (int) $request->input('meeting');

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

            $detail->schedule_id = $scheduleArr[0];
            $detail->price = $schedule->course->price;
            $price = $schedule->course->price;

            // Check discount and subtotal
            $finalSubtotal = 0;
//            $discount = 0;
//            if($request->filled('discount')){
//                $discountStr = str_replace('.','', $request->input('discount'));
//                $discount = (double) $discountStr;
//                $detail->discount = $discount;
//                $finalSubtotal = $price - $discount;
//                $detail->subtotal = $finalSubtotal;
//            }
//            else{
//                $finalSubtotal = $price;
//                $detail->subtotal = $finalSubtotal;
//            }
            $finalSubtotal = $meeting * $price;
            $detail->meeting_amount = $meeting;
            $detail->subtotal = $finalSubtotal;
            $detail->save();

            // Accumulate total price, discount & payment
            $header->total_price += $price;

            $header->total_payment += $finalSubtotal;
//            if($request->filled('discount')){
//                $header->total_discount += $discount;
//            }
            $header->save();

            // Activate related schedule
            $schedule = $detail->schedule;
            $schedule->status_id = 3;
            $schedule->save();

            return new JsonResponse($detail);
        }
        catch (\Exception $ex){
            error_log($ex);
            return Response::json(array('errors' => 'INVALID'));
        }
    }

    public function update(Request $request){
        try{
            $meeting = (int) $request->input('meeting');

            $now = Carbon::now('Asia/Jakarta');
            $user = Auth::user();

            $detail = TransactionDetail::find($request->input('id'));
            $detail->updated_by = $user->id;
            $detail->updated_at = $now->toDateTimeString();

            // Get old value
            $oldPrice = $detail->price;
            $oldSubtotal = $detail->subtotal;
//            $oldDiscountAmount =  $detail->discount ?? 0;

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
//            $finalSubtotal = 0;
//            $discountAmount = 0;
//            if($request->filled('discount') && $request->input('discount') != '0'){
//                $discountStr = str_replace('.','', $request->input('discount'));
//                $discountAmount = (double) $discountStr;
//                $detail->discount = $discountAmount;
//                $finalSubtotal = $price - $discountAmount;
//                $detail->subtotal = $finalSubtotal;
//            }
//            else{
//                $detail->discount = 0;
//                $finalSubtotal = $price;
//                $detail->subtotal = $finalSubtotal;
//            }
            $finalSubtotal = $meeting * $price;
            $detail->meeting_amount = $meeting;
            $detail->subtotal = $finalSubtotal;
            $detail->save();

            // Accumulate total price & payment
            $header = TransactionHeader::find($detail->header_id);
            $totalPrice = $header->total_price - $oldSubtotal + $finalSubtotal;
            $header->total_price = $totalPrice;
//            $header->total_discount = $header->total_discount - $oldDiscountAmount + $discountAmount;
//            $header->total_payment = $header->registration_fee + $totalPrice - $header->total_discount;
            $header->total_payment = $header->registration_fee + $totalPrice;

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

            // Validate attendance
//            $attendance = Attendance::where('schedule_id', $detail->schedule_id)
//                ->where('customer_id', $detail->schedule->customer_id)
//                ->first();
//            if(!empty($attendance)){
//                return Response::json(array('errors' => 'USED'));
//            }

            // Get old value
//            $oldPrice = $detail->price;
//            $oldDiscountAmount = $detail->discount;
            $oldSubtotal = $detail->subtotal;

            // Minus header total values
            $header = $detail->transaction_header;
            $header->total_price -= $oldSubtotal;
//            $header->total_discount -= $oldDiscountAmount;
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