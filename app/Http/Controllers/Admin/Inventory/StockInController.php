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
use App\Models\StockCard;
use App\Models\StockIn;
use App\Models\Warehouse;
use App\Transformer\Inventory\StockInTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class StockInController extends Controller
{
    public function index(){
        return View('admin.inventory.stock_ins.index');
    }

    public function create(){
        $warehouses = Warehouse::where('id', '>', 0)->get();

        return View('admin.inventory.stock_ins.create', compact('warehouses'));
    }


    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'item'      => 'required',
            'increase'      => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        if(Input::get('warehouse') === '-1'){
            return redirect()->back()->withErrors('Pilih gudang!', 'default')->withInput($request->all());
        }

        //add to stock in table
        $user = Auth::user();
        $now = Carbon::now('Asia/Jakarta');
        $increase = (int) str_replace('.','', $request->input('increase'));
        $selectedItems = $request->input('item');
        $selectedItem = $selectedItems[0];

//        dd($selectedItem. " ". $request->input('warehouse'));
        $item = StockIn::create([
            'item_id'          => $selectedItem,
            'increase'          => $increase,
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
                'stock'        => $increase,
                'created_by'    => $user->id,
                'created_at'    => $now
            ]);
            $itemStockPerWarehouse = $increase;
        }
        else{
            $oldStock = $itemStockDB->stock;
            $itemStockPerWarehouse = $oldStock + $increase;

            $itemStockDB->stock = $itemStockPerWarehouse;
            $itemStockDB->updated_by = $user->id;
            $itemStockDB->updated_at = $now;

            $itemStockDB->save();


        }

        //edit item
        $itemDB = Item::find($selectedItem);
        $itemDB->stock += $increase;
        $itemDB->save();

        //add stock card item
        $stockCard = StockCard::create([
            'item_id'          => $selectedItem,
            'change'          => $increase,
            'stock'          => $itemStockPerWarehouse,
            'flag'          => "-",
            'description'   => "stock ins",
            'warehouse_id'  => $request->input('warehouse'),
            'created_by'    => $user->id,
            'created_at'    => $now
        ]);


        Session::flash('message', 'Berhasil membuat data Stock In baru!');

        return redirect()->route('admin.stock_ins');
    }

    public function getIndex(){
        $stockIns = StockIn::all();
        return DataTables::of($stockIns)
            ->setTransformer(new StockInTransformer)
            ->addIndexColumn()
            ->make(true);
    }
}