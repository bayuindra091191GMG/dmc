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
use App\Models\StockAdjustment;
use App\Transformer\MasterData\StockAdjustmentTransformer;
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
        return View('admin.stock.stock_adjustments.index');
    }

    public function create(){
        return View('admin.stock.stock_adjustments.create');
    }


    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name'      => 'required|max:100',
            'code'     => 'required|max:45'
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

        if(Input::get('uom') === '-1'){
            return redirect()->back()->withErrors('Pilih uom!', 'default')->withInput($request->all());
        }

        if(Input::get('group') === '-1'){
            return redirect()->back()->withErrors('Pilih group!', 'default')->withInput($request->all());
        }

        $user = Auth::user();
        $now = Carbon::now('Asia/Jakarta');

        $item = Item::create([
            'name'          => Input::get('name'),
            'code'          => Input::get('code'),
            'uom_id'        => Input::get('uom'),
            'warehouse_id'  => Input::get('warehouse'),
            'group_id'      => Input::get('group'),
            'created_by'    => $user->id,
            'created_at'    => $now
        ]);

        if(!empty(Input::get('description'))){
            $item->description = Input::get('description');
            $item->save();
        }

        Session::flash('message', 'Berhasil membuat data barang baru!');

        return redirect()->route('admin.stock_adjustments');
    }

    public function getIndex(){
        $stockAdjustments = StockAdjustment::all();
        return DataTables::of($stockAdjustments)
            ->setTransformer(new StockAdjustmentTransformer())
            ->addIndexColumn()
            ->make(true);
    }
}