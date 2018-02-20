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
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class QuotationDetailController extends Controller
{
    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'item'      => 'required',
            'qty'       => 'required',
            'price'     => 'required',
            'remark'    => 'max:200'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $detail = new QuotationDetail();
        $detail->header_id = Input::get('header_id');
        $detail->item_id = Input::get('item');
        $detail->price = str_replace('.','', Input::get('price'));
        $detail->discount = Input::get('discount');

        if(!empty(Input::get('remark'))) $detail->remark = Input::get('remark');

        $detail->save();

        $json = QuotationDetail::with('item')->find($detail->id);
        return new JsonResponse($json);
    }

    public function update(Request $request){
        $validator = Validator::make($request->all(),[
            'item'      => 'required',
            'qty'       => 'required',
            'price'     => 'required',
            'remark'    => 'max:200'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $detail = QuotationDetail::find(Input::get('id'));
        $detail->item_id = Input::get('item');
        $detail->price = str_replace('.','', Input::get('price'));
        $detail->discount = Input::get('discount');

        if(!empty(Input::get('remark'))) $detail->remark = Input::get('remark');

        $detail->save();

        $json = QuotationDetail::with('item')->find($detail->id);
        return new JsonResponse($json);
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