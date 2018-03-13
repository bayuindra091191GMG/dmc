<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 25/01/2018
 * Time: 10:15
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Libs\Utilities;
use App\Models\Group;
use App\Models\Item;
use App\Models\ItemStock;
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
use Illuminate\Validation\Rule;
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
            'code'          => 'required|max:45|regex:/^\S*$/u|unique:items',
            'name'          => 'required|max:100',
            'part_number'   => 'max:45',
            'description'   => 'max:200'
        ],[
            'code.unique'   => 'Kode barang telah terpakai',
            'code.regex'    => 'Kode barang harus tanpa spasi'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        if(Input::get('uom') === '-1'){
            return redirect()->back()->withErrors('Pilih uom!', 'default')->withInput($request->all());
        }

        if(Input::get('group') === '-1'){
            return redirect()->back()->withErrors('Pilih group!', 'default')->withInput($request->all());
        }
        // Validate machinery type

        $machinery_types = $request->input('machinery_type');
        if(count($machinery_types) == 0) {
            return redirect()->back()->withErrors('Tipe alat berat harus diisi!', 'default')->withInput($request->all());
        }

        // Validate warehouse
        $warehouses = $request->input('warehouse');
        $qtys = $request->input('qty');
        $valid = true;
        $isStock = false;
        if(count($warehouses) > 0){
            $idx = 0;
            foreach($warehouses as $warehouse){
                if(empty($qtys[$idx])){
                    $valid = false;
                }
                else{
                    $isStock = true;
                }
                $idx++;
            }

            if(!$valid){
                return redirect()->back()->withErrors('Detail gudang dan jumlah stok wajib diisi!', 'default')->withInput($request->all());
            }
        }

        // Validate duplicated values
        if($isStock){
            $valid = Utilities::arrayIsUnique($warehouses);
            if(!$valid){
                return redirect()->back()->withErrors('Detail gudang tidak boleh kembar!', 'default')->withInput($request->all());
            }
        }

        $user = Auth::user();
        $now = Carbon::now('Asia/Jakarta');

        $item = Item::create([
            'name'          => $request->input('name'),
            'code'          => $request->input('code'),
            'uom_id'        => $request->input('uom'),
            'group_id'      => $request->input('group'),
            'machinery_type_id'      => $machinery_types[0],
            'created_by'    => $user->id,
            'created_at'    => $now->toDateTimeString()
        ]);

        if(!empty(Input::get('valuation')) && Input::get('valuation') != "0"){
            $value = str_replace('.','', Input::get('valuation'));
            $item->value = $value;
        }

        if(!empty(Input::get('description'))){
            $item->description = Input::get('description');
            $item->save();
        }

        // Get stock
        if(count($warehouses) > 0){
            $idx = 0;
            $totalStock = 0;
            foreach($warehouses as $warehouse){
                if(!empty($warehouse)){
                    $stock = ItemStock::create([
                        'item_id'       => $item->id,
                        'warehouse_id'  => $warehouse,
                        'stock'         => $qtys[$idx],
                        'created_by'    => $user->id,
                        'created_at'    => $now->toDateTimeString()
                    ]);

                    $totalStock = $totalStock + (int) $qtys[$idx];
                }
                $idx++;
            }

            $item->stock = $totalStock;
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
            'name'          => 'required|max:100',
            'part_number'   => 'max:45',
            'description'   => 'max:200'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::user();
        $now = Carbon::now('Asia/Jakarta');

        $item->name = $request->input('name');
        $item->part_number = $request->input('part_number');
        $item->uom_id = $request->input('uom');
        $item->group_id = $request->input('group');
        $item->description = $request->input('description');
        $item->updated_by = $user->id;
        $item->updated_at = $now;

        $item->save();

        Session::flash('message', 'Berhasil mengubah data barang!');

        return redirect()->route('admin.items.edit', ['item' => $item]);
    }

    public function destroy(Request $request)
    {
        try{
            $item = Item::find($request->input('id'));
            $item->delete();

            Session::flash('message', 'Berhasil menghapus data barang '. $item->code. ' - '. $item->name);
            return Response::json(array('success' => 'VALID'));
        }
        catch(\Exception $ex){
            return Response::json(array('errors' => 'INVALID'));
        }
    }

    public function getIndex(){
        $items = Item::all();
        return DataTables::of($items)
            ->setTransformer(new ItemTransformer)
            ->addIndexColumn()
            ->make(true);
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

    public function getExtendedItems(Request $request){
        $term = trim($request->q);
        $items = Item::where('code', 'LIKE', '%'. $term. '%')
            ->orWhere('name', 'LIKE', '%'. $term. '%')
            ->get();

        $formatted_tags = [];

        foreach ($items as $item) {
            $createdDate = Carbon::parse($item->created_at)->format('d M Y');
            $formatted_tags[] = ['id' => $item->id. '#'. $item->code. '#'. $item->name. '#'. $item->uomDescription, 'text' => $item->code. ' - '. $item->name. ' - '. $createdDate];
        }

        return \Response::json($formatted_tags);
    }
}