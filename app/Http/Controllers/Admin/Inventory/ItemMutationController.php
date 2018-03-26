<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 25/01/2018
 * Time: 10:15
 */

namespace App\Http\Controllers\Admin\Inventory;


use App\Http\Controllers\Controller;
use App\Models\ItemMutation;
use App\Models\ItemStock;
use App\Models\Warehouse;
use App\Transformer\Inventory\ItemMutationTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class ItemMutationController extends Controller
{
    public function index(){
        return View('admin.inventory.item_mutations.index');
    }

    public function create(){
        $warehouses = Warehouse::where('id', '>', 0)->get();

        return View('admin.inventory.item_mutations.create', compact('warehouses'));
    }


    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'item'      => 'required',
            'warehouse_from'      => 'required',
            'warehouse_to'      => 'required',
            'mutation_quantity'      => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }
        if(Input::get('warehouse_from') === '-1'){
            return redirect()->back()->withErrors('Pilih gudang asal!', 'default')->withInput($request->all());
        }
        if(Input::get('warehouse_to') === '-1'){
            return redirect()->back()->withErrors('Pilih gudang tujuan!', 'default')->withInput($request->all());
        }
        if(Input::get('warehouse_from') === Input::get('warehouse_to')){
            return redirect()->back()->withErrors('Pilih gudang asal dan gudang tujuan yang berbeda', 'default')->withInput($request->all());
        }
        if(Input::get('mutation_quantity') <= 0){
            return redirect()->back()->withErrors('Jumlah barang harus lebih dari 0!', 'default')->withInput($request->all());
        }

        $selectedItems = Input::get('item');
        $selectedItem = $selectedItems[0];
        $stockWarehouseFromDB = ItemStock::where('warehouse_id', Input::get('warehouse_from'))
            ->where('item_id', $selectedItem)->first();

        if($stockWarehouseFromDB->stock < Input::get('mutation_quantity')){
            return redirect()->back()->withErrors('Jumlah barang harus lebih dari stok gudang!', 'default')->withInput($request->all());
        }

        $user = Auth::user();
        $now = Carbon::now('Asia/Jakarta');

        $item = ItemMutation::create([
            'item_id'          => $selectedItem,
            'from_warehouse_id'          => Input::get('warehouse_from'),
            'to_warehouse_id'          => Input::get('warehouse_to'),
            'mutation_quantity'          => Input::get('mutation_quantity'),
            'created_by'    => $user->id,
            'created_at'    => $now
        ]);

        Session::flash('message', 'Berhasil membuat Mutasi Barang baru!');

        return redirect()->route('admin.item_mutations');
    }

    public function getIndex(){
        $stockIns = ItemMutation::all();
        return DataTables::of($stockIns)
            ->setTransformer(new ItemMutationTransformer())
            ->addIndexColumn()
            ->make(true);
    }
}