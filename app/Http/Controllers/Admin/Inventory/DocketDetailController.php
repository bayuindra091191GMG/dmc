<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use App\Models\IssuedDocketDetail;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

class DocketDetailController extends Controller
{
    public function store(Request $request){
        try{
            $validator = Validator::make($request->all(),[
                'item'      => 'required',
                'qty'       => 'required',
                'time'      => 'required',
                'remark'    => 'max:200'
            ]);

            if ($validator->fails()) {
                return Response::json(array('errors' => $validator->getMessageBag()->toArray()));
            }

            $detail = new IssuedDocketDetail();
            $detail->header_id = Input::get('header_id');
            $detail->item_id = Input::get('item');
            $detail->quantity = Input::get('qty');
            $detail->time = Input::get('time');

            if(!empty(Input::get('remark'))) $detail->remarks = Input::get('remark');

            $detail->save();

            error_log($detail->id);

            $json = IssuedDocketDetail::with('item')->find($detail->id);
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
                'time'      => 'required',
                'remark'    => 'max:200'
            ]);

            if ($validator->fails()) {
                return Response::json(array('errors' => $validator->getMessageBag()->toArray()));
            }

            $detail = IssuedDocketDetail::find(Input::get('id'));

            if(!empty(Input::get('item'))){
                $detail->item_id = Input::get('item');
            }

            $detail->quantity = Input::get('qty');
            $detail->remarks = Input::get('remark');
            $detail->time = Input::get('time');

            if(!empty(Input::get('date'))){
                $date = Carbon::createFromFormat('d M Y', Input::get('date'), 'Asia/Jakarta');
                $detail->delivery_date = $date;
            }

            $detail->save();

            $json = IssuedDocketDetail::with('item')->find($detail->id);
        }
        catch (\Exception $ex){
            error_log($ex);
        }

        return new JsonResponse($json);
    }

    public function delete(Request $request){
        try{
            //Check for minimun 1 Detail
            $details = IssuedDocketDetail::where('header_id', Input::get('header_id'))->get();
            if($details->count() == 1){
                return Response::json(array('errors' => 'INVALID'));
            }

            $detail = IssuedDocketDetail::find(Input::get('id'));
            $detail->delete();

            return new JsonResponse($detail);
        }
        catch (\Exception $ex){
            error_log($ex);
        }
    }
}
