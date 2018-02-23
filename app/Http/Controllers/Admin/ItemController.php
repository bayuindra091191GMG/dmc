<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 25/01/2018
 * Time: 10:15
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Item;
use App\Models\Uom;
use App\Models\Warehouse;
use App\Transformer\MasterData\ItemTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class ItemController extends Controller
{
    public function index(){
        return View('admin.items.index');
    }

    public function show(Item $item){
        $selectedItem = $item;

        return View('admin.items.show', compact('selectedItem'));
    }

    public function create(){
        $warehouses = Warehouse::all();
        $uoms = Uom::all();
        $groups = Group::all();

        $data = [
            'warehouses'    => $warehouses,
            'uoms'          => $uoms,
            'groups'        => $groups
        ];

        return View('admin.items.create')->with($data);
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

        return redirect()->route('admin.items');
    }

    public function edit(Item $item){
        $warehouses = Warehouse::all();
        $uoms = Uom::all();
        $groups = Group::all();

        $data = [
            'item'          => $item,
            'warehouses'    => $warehouses,
            'uoms'          => $uoms,
            'groups'        => $groups
        ];

        return View('admin.items.edit')->with($data);
    }

    public function update(Request $request, Item $item){
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

        $user = Auth::user();
        $now = Carbon::now('Asia/Jakarta');

        $item->name = Input::get('name');
        $item->code = Input::get('code');
        $item->uom_id = Input::get('uom');
//        $item->warehouse_id = Input::get('warehouse');
        $item->group_id = Input::get('group');
        $item->description = Input::get('description');
        $item->updated_by = $user->id;
        $item->updated_at = $now;

        $item->save();

        Session::flash('message', 'Berhasil mengubah data barang!');

        return redirect()->route('admin.items.edit', ['item' => $item]);
    }

    public function getIndex(){
        $items = Item::all();
        return DataTables::of($items)
            ->setTransformer(new ItemTransformer)
            ->addIndexColumn()
            ->make(true);
    }

    public function getWarehouse(Request $request){
        $term = trim($request->q);
        $warehouses = Warehouse::where('name', 'LIKE', '%'. $term. '%')->get();

        $formatted_tags = [];

        foreach ($warehouses as $warehouse) {
            $formatted_tags[] = ['id' => $warehouse->id, 'text' => $warehouse->name];
        }

        return Response::json($formatted_tags);
    }

    public function getItems(Request $request){
        $term = trim($request->q);
        $items = Item::where('code', 'LIKE', '%'. $term. '%')
            ->orWhere('name', 'LIKE', '%'. $term. '%')
            ->get();

        $formatted_tags = [];

        foreach ($items as $item) {
            $createdDate = Carbon::parse($item->created_at)->format('d M Y');
            $formatted_tags[] = ['id' => $item->id, 'text' => $item->code. ' - '. $item->name. ' - '. $createdDate];
        }

        return \Response::json($formatted_tags);
    }
}