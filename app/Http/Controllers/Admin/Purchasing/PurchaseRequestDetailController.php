<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 08/02/2018
 * Time: 15:05
 */

namespace App\Http\Controllers\Admin\Purchasing;


use App\Http\Controllers\Controller;
use App\Models\PurchaseRequestDetail;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class PurchaseRequestDetailController extends Controller
{
    public function store(Request $request){
        try{
            $validator = Validator::make($request->all(),[
                'item'      => 'required',
                'qty'       => 'required',
                'remark'    => 'max:200'
            ]);

            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $detail = new PurchaseRequestDetail();
            $detail->header_id = Input::get('header_id');
            $detail->item_id = Input::get('item');
            $detail->quantity = Input::get('qty');

            if(!empty(Input::get('remark'))) $detail->remark = Input::get('remark');

            $detail->save();

            $json = PurchaseRequestDetail::with('item')->find($detail->id);
        }
        catch(\Exception $ex){
            error_log($ex);
        }

        return new JsonResponse($json);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request){
        try{
            $validator = Validator::make($request->all(),[
                'qty'       => 'required',
                'remark'    => 'max:200'
            ]);

            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $detail = PurchaseRequestDetail::find(Input::get('id'));

            if(!empty(Input::get('item'))){
                $detail->item_id = Input::get('item');
            }

            $detail->quantity = Input::get('qty');
            $detail->remark = Input::get('remark');

            if(!empty(Input::get('date'))){
                $date = Carbon::createFromFormat('d M Y', Input::get('date'), 'Asia/Jakarta');
                $detail->delivery_date = $date;
            }

            $detail->save();

            $json = PurchaseRequestDetail::with('item')->find($detail->id);
        }
        catch (\Exception $ex){
            error_log($ex);
        }

        return new JsonResponse($json);
    }

    public function delete(Request $request){
        try{
            $detail = PurchaseRequestDetail::find(Input::get('id'));
            $detail->delete();

            return new JsonResponse($detail);
        }
        catch (\Exception $ex){
            error_log($ex);
        }
    }
}