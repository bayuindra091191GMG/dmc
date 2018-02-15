<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 25/01/2018
 * Time: 10:15
 */

namespace App\Http\Controllers\Admin\stock;


use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\StockIn;
use App\Transformer\Stock\StockInTransformer;
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
        return View('admin.stock.stock_ins.index');
    }

    public function create(){
        return View('admin.stock.stock_ins.create');
    }


    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'item'      => 'required',
            'increase'      => 'required',
            'new_stock'     => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        //add to stock in table
        $user = Auth::user();
        $now = Carbon::now('Asia/Jakarta');
        $increase = (int) str_replace('.','', Input::get('increase'));
        $newStock = (int) str_replace('.','', Input::get('new_stock'));
        $selectedItems = Input::get('item');
        $selectedItem = $selectedItems[0];

        $item = StockIn::create([
            'item_id'          => $selectedItem,
            'increase'          => $increase,
            'new_stock'        => $newStock,
            'created_by'    => $user->id,
            'created_at'    => $now
        ]);

        //edit item stock
//        $itemDB = Item::find($selectedItem);
//        $itemDB->stock = $newStock;
//        $itemDB->save();

        Session::flash('message', 'Berhasil membuat data Stock In baru!');

        return redirect()->route('admin.stock_ins');
    }

    public function getIndex(){
        $stockIns = StockIn::all();
        return DataTables::of($stockIns)
            ->setTransformer(new StockInTransformer())
            ->addIndexColumn()
            ->make(true);

    }
}