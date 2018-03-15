<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 15/03/2018
 * Time: 11:27
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\DeliveryOrderDetail;
use App\Models\IssuedDocketDetail;
use App\Models\Item;
use App\Models\ItemReceiptDetail;
use App\Models\ItemStock;
use App\Models\PurchaseOrderDetail;
use App\Models\PurchaseRequestDetail;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class ItemStockController extends Controller
{
    public function store(Request $request){
        try{
            $validator = Validator::make($request->all(),[
                'warehouse_id'      => 'required',
                'stock'             => 'required'
            ]);

            if ($validator->fails()) {
                return Response::json(array('errors' => $validator->getMessageBag()->toArray()));
            }

            $itemId = $request->input('item_id');
            $stockAdd = (int) $request->input('stock');

            $isPrUsed = PurchaseRequestDetail::where('item_id', $itemId)->exists();
            $isPoUsed = PurchaseOrderDetail::where('item_id', $itemId)->exists();
            $isGrUsed = ItemReceiptDetail::where('item_id', $itemId)->exists();
            $isIdUsed = IssuedDocketDetail::where('item_id', $itemId)->exists();
            $isDoUsed = DeliveryOrderDetail::where('item_id', $itemId)->exists();

            $isUsed = false;
            if($isPrUsed || $isPoUsed || $isGrUsed || $isIdUsed || $isDoUsed){
                $isIdUsed = true;
            }

            if($isUsed){
                return Response::json(array('errors' => 'used'));
            }

            $user = \Auth::user();
            $now = Carbon::now('Asia/Jakarta');

            // Check exists
            $stock = ItemStock::where('item_id', $itemId)
                ->where('warehouse_id', $request->input('warehouse_id'))
                ->first();
            if(!empty($stock)){
//                $stock->stock += $stockAdd;
//                $stock->updated_by = $user->id;
//                $stock->updated_at = $now->toDateTimeString();
//                $stock->save();
                return Response::json(array('errors' => 'exists'));
            }
            else{
                $stock = ItemStock::create([
                    'item_id'       => $itemId,
                    'warehouse_id'  => $request->input('warehouse_id'),
                    'stock'         => $stockAdd,
                    'created_by'    => $user->id,
                    'updated_by'    => $user->id,
                    'created_at'    => $now->toDateTimeString()
                ]);
            }

            // Edit total stock
            $item = Item::find($itemId);
            $item->stock += $stockAdd;
            $item->save();

            $json = ItemStock::with('warehouse')->find($stock->id);
            error_log($json->warehouse->name);
            return new JsonResponse($json);
        }
        catch(\Exception $ex){
            error_log($ex);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request){
        try{
            $validator = Validator::make($request->all(),[
                'stock'             => 'required'
            ]);

            if ($validator->fails()) {
                return Response::json(array('errors' => $validator->getMessageBag()->toArray()));
            }

            $itemId = $request->input('item_id');
            $stockAdd = (int) $request->input('stock');

            $isPrUsed = PurchaseRequestDetail::where('item_id', $itemId)->exists();
            $isPoUsed = PurchaseOrderDetail::where('item_id', $itemId)->exists();
            $isGrUsed = ItemReceiptDetail::where('item_id', $itemId)->exists();
            $isIdUsed = IssuedDocketDetail::where('item_id', $itemId)->exists();
            $isDoUsed = DeliveryOrderDetail::where('item_id', $itemId)->exists();

            $isUsed = false;
            if($isPrUsed || $isPoUsed || $isGrUsed || $isIdUsed || $isDoUsed){
                $isUsed = true;
            }

            if($isUsed){
                return Response::json(array('errors' => 'used'));
            }

            $user = \Auth::user();
            $now = Carbon::now('Asia/Jakarta');

            // Check exists
            $oldStock = 0;
            $stock = ItemStock::find($request->input('id'));
            if(!empty($stock)){

                $exists = ItemStock::where('item_id', $itemId)
                    ->where('warehouse_id', $request->input('warehouse_id'))
                    ->exists();

                if($exists){
                    return Response::json(array('errors' => 'exists'));
                }
                else{
                    $oldStock = $stock->stock;

                    if($request->filled('warehouse_id')) $stock->warehouse_id = $request->input('warehouse_id');

                    $stock->stock = $stockAdd;
                    $stock->updated_by = $user->id;
                    $stock->updated_at = $now->toDateTimeString();
                    $stock->save();
                }
            }
            else{
                return Response::json(array('errors' => 'deleted'));
//                $stock = ItemStock::create([
//                    'item_id'       => $itemId,
//                    'warehouse_id'  => $request->filled('warehouse_id') ? $request->input('warehouse_id') : null,
//                    'stock'         => $stockAdd,
//                    'created_by'    => $user->id,
//                    'created_at'    => $now->toDateTimeString(),
//                    'updated_by'    => $user->id
//                ]);
            }

            // Edit total stock
            $item = Item::find($itemId);
            $item->stock = $item->stock - $oldStock + $stockAdd;
            $item->save();

            $json = ItemStock::with('warehouse')->find($stock->id);
            return new JsonResponse($json);
        }
        catch (\Exception $ex){
            error_log($ex);
        }
    }

    public function destroy(Request $request){
        try{
            $itemId = $request->input('item_id');

            $isPrUsed = PurchaseRequestDetail::where('item_id', $itemId)->exists();
            $isPoUsed = PurchaseOrderDetail::where('item_id', $itemId)->exists();
            $isGrUsed = ItemReceiptDetail::where('item_id', $itemId)->exists();
            $isIdUsed = IssuedDocketDetail::where('item_id', $itemId)->exists();
            $isDoUsed = DeliveryOrderDetail::where('item_id', $itemId)->exists();

            $isUsed = false;
            if($isPrUsed || $isPoUsed || $isGrUsed || $isIdUsed || $isDoUsed){
                $isUsed = true;
            }

            if($isUsed){
                return Response::json(array('errors' => 'used'));
            }

            $stock = ItemStock::find($request->input('id'));

            if(empty($stock)) return new JsonResponse($stock);

            $stock->delete();

            // Minus total stock
            $item = Item::find($itemId);
            $item->stock = $item->stock - $stock->stock;
            $item->save();

            return new JsonResponse($stock);
        }
        catch (\Exception $ex){
            error_log($ex);
        }
    }
}