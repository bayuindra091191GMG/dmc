<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 25/01/2018
 * Time: 10:15
 */

namespace App\Http\Controllers\Admin\Inventory;


use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\ItemStock;
use App\Models\StockAdjustment;
use App\Models\StockCard;
use App\Models\Warehouse;
use App\Transformer\Inventory\StockAdjustmentTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class StockAdjustmentController extends Controller
{
    public function index(){
        return View('admin.inventory.stock_adjustments.index');
    }

    public function create(){
        $warehouses = Warehouse::all();

        return View('admin.inventory.stock_adjustments.create', compact('warehouses'));
    }


    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'item'      => 'required',
            'depreciation'      => 'required',
            'warehouse_id'  => $request->input('warehouse'),
//            'new_stock'     => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        //add to stock adjustment table
        $user = Auth::user();
        $now = Carbon::now('Asia/Jakarta');
        $depreciation = (int) str_replace('.','', Input::get('depreciation'));
//        $newStock = (int) str_replace('.','', Input::get('new_stock'));
        $selectedItems = Input::get('item');
        $selectedItem = $selectedItems[0];

        $item = StockAdjustment::create([
            'item_id'          => $selectedItem,
            'depreciation'          => $depreciation,
            'warehouse_id'  => $request->input('warehouse'),
            'created_by'    => $user->id,
            'created_at'    => $now
        ]);

        //edit item_stock
        $itemStockDB = ItemStock::where('item_id', $selectedItem)->where('warehouse_id', $request->input('warehouse'))->first();
        if(empty($itemStockDB)){
            $itemStock = ItemStock::create([
                'item_id'          => $selectedItem,
                'warehouse_id'  => $request->input('warehouse'),
                'stock'        => $depreciation,
                'created_by'    => $user->id,
                'created_at'    => $now
            ]);
            $itemStockPerWarehouse = $depreciation;
        }
        else{
            $oldStock = $itemStockDB->stock;
            $itemStockPerWarehouse = $oldStock - $depreciation;

            $itemStockDB->stock = $itemStockPerWarehouse;
            $itemStockDB->updated_by = $user->id;
            $itemStockDB->updated_at = $now;

            $itemStockDB->save();
        }

        //edit item
        $itemDB = Item::find($selectedItem);
        $itemDB->stock -= $depreciation;
        $itemDB->save();

        //add stock card item
        $stockCard = StockCard::create([
            'item_id'          => $selectedItem,
            'change'          => $depreciation,
            'stock'          => $itemStockPerWarehouse,
            'warehouse_id'  => $request->input('warehouse'),
            'created_by'    => $user->id,
            'created_at'    => $now
        ]);


        Session::flash('message', 'Berhasil membuat data Stock Adjustment baru!');

        return redirect()->route('admin.stock_adjustments');
    }

    public function getIndex(){
        $stockAdjustments = StockAdjustment::all();
        return DataTables::of($stockAdjustments)
            ->setTransformer(new StockAdjustmentTransformer)
            ->addIndexColumn()
            ->make(true);
    }
}