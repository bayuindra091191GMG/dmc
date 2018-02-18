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
use App\Models\StockAdjustment;
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
        return View('admin.inventory.stock_adjustments.create');
    }


    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'item'      => 'required',
            'depreciation'      => 'required',
            'new_stock'     => 'required'
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
        $newStock = (int) str_replace('.','', Input::get('new_stock'));
        $selectedItems = Input::get('item');
        $selectedItem = $selectedItems[0];

        $item = StockAdjustment::create([
            'item_id'          => $selectedItem,
            'depreciation'          => $depreciation,
            'new_stock'        => $newStock,
            'created_by'    => $user->id,
            'created_at'    => $now
        ]);

        //edit item stock
//        $itemDB = Item::find($selectedItem);
//        $itemDB->stock = $newStock;
//        $itemDB->save();


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