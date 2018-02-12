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
    public function edit(){

    }

    /**
     * @param Request $request
     * @param PurchaseRequestDetail $detail
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
}