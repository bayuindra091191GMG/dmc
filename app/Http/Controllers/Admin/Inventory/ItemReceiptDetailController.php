<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use App\Models\ItemReceiptDetail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;

class ItemReceiptDetailController extends Controller
{
    public function store(Request $request){
        try{
            $validator = Validator::make($request->all(),[
                'header_id' => 'required',
                'item'      => 'required',
                'qty'       => 'required',
                'po'        => 'required',
                'remark'    => 'max:200'
            ]);

            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $detail = new ItemReceiptDetail();
            $detail->header_id = Input::get('header_id');
            $detail->item_id = Input::get('item');
            $detail->quantity = Input::get('qty');
            $detail->purchase_order_id = Input::get('po');

            if(!empty(Input::get('remark'))) $detail->remark = Input::get('remark');

            $detail->save();

            error_log($detail->id);

            $json = ItemReceiptDetail::with('item')->find($detail->id);
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

            $detail = ItemReceiptDetail::find(Input::get('id'));

            if(!empty(Input::get('item'))){
                $detail->item_id = Input::get('item');
            }
            if(!empty(Input::get('po'))){
                $detail->purchase_order_id = Input::get('po');
            }

            $detail->quantity = Input::get('qty');
            $detail->remark = Input::get('remark');

            if(!empty(Input::get('date'))){
                $date = Carbon::createFromFormat('d M Y', Input::get('date'), 'Asia/Jakarta');
                $detail->delivery_date = $date;
            }

            $detail->save();

            $json = ItemReceiptDetail::with('item')->find($detail->id);
        }
        catch (\Exception $ex){
            error_log($ex);
        }

        return new JsonResponse($json);
    }

    public function delete(Request $request){
        try{
            $detail = ItemReceiptDetail::find(Input::get('id'));
            $detail->delete();

            return new JsonResponse($detail);
        }
        catch (\Exception $ex){
            error_log($ex);
        }
    }
}
