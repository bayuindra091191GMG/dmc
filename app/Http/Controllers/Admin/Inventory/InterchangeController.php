<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 25/01/2018
 * Time: 10:15
 */
namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Interchange;
use App\Models\Item;
use App\Models\Uom;
use App\Models\Warehouse;
use App\Transformer\Inventory\InterchangeTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class InterchangeController extends Controller
{
    public function index(){
        return View('admin.inventory.interchanges.index');
    }

    public function create(){
        $warehouses = Warehouse::all();
        $uoms = Uom::all();
        $groups = Group::all();

        $data = [
            'warehouses'        => $warehouses,
            'uoms'              => $uoms,
            'groups'            => $groups
        ];

        return View('admin.inventory.interchanges.create')->with($data);
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

        if(empty(Input::get('item_id_before'))){
            return redirect()->back()->withErrors('Pilih barang sebelumnya!', 'default')->withInput($request->all());
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

        //Create Interchange
        $interchange = Interchange::create([
            'item_id_before'    => Input::get('item_id_before'),
            'item_id_after'     => $item->id,
            'created_by'        => $user->id,
            'created_at'        => $now
        ]);

        Session::flash('message', 'Berhasil membuat Interchange!');

        return redirect()->route('admin.interchanges');
    }

    public function getIndex(){
        $items = Interchange::all();
        return DataTables::of($items)
            ->setTransformer(new InterchangeTransformer())
            ->addIndexColumn()
            ->make(true);
    }
}