<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 25/01/2018
 * Time: 10:15
 */
namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use App\Libs\Utilities;
use App\Models\Group;
use App\Models\Interchange;
use App\Models\Item;
use App\Models\ItemStock;
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
            'code'          => 'required|max:45|regex:/^\S*$/u|unique:items',
            'name'          => 'required|max:100',
            'uom'           => 'required|max:30',
            'part_number'   => 'max:45',
            'description'   => 'max:200'
        ],[
            'code.unique'   => 'Kode inventory telah terpakai',
            'code.regex'    => 'Kode inventory harus tanpa spasi'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        if(!$request->filled('item_id_before')){
            return redirect()->back()->withErrors('Pilih Inventory sebelumnya!', 'default')->withInput($request->all());
        }

        if($request->input('group') === '-1'){
            return redirect()->back()->withErrors('Pilih Kategori Inventory!', 'default')->withInput($request->all());
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
            'name'              => $request->input('name'),
            'code'              => $request->input('code'),
            'part_number'       => $request->input('part_number'),
            'uom'               => $request->input('uom'),
            'machinery_type'    => $request->input('machinery_type'),
            'group_id'          => $request->input('group'),
            'created_by'        => $user->id,
            'created_at'        => $now
        ]);

        if($request->filled('valuation') && $request->input('valuation') != "0"){
            $value = str_replace('.','', Input::get('valuation'));
            $item->value = $value;
        }

        if($request->filled('description')){
            $item->description = Input::get('description');
        }

        $item->save();

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

        //Create Interchange
        $interchange = Interchange::create([
            'item_id_before'    => $request->input('item_id_before'),
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
            ->setTransformer(new InterchangeTransformer)
            ->addIndexColumn()
            ->make(true);
    }
}