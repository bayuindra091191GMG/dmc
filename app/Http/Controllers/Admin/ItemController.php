<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 25/01/2018
 * Time: 10:15
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Uom;
use App\Models\Warehouse;
use App\Transformer\MasterData\ItemTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Yajra\DataTables\DataTables;

class ItemController extends Controller
{
    public function index(){
        return View('admin.items');
    }

    public function create(){
        $warehouses = Warehouse::all();
        $uoms = Uom::all();

        $data = [
            'warehouses'    => $warehouses,
            'uoms'          => $uoms
        ];

        return View('admin.items.create')->with($data);
    }

    public function store(Request $request){

    }

    public function edit($item){

    }

    public function update(Request $request, Item $item){

    }

    public function getIndex(){
        $items = Item::all();
        return DataTables::of($items)
            ->setTransformer(new ItemTransformer)
            ->make(true);
    }

    public function getWarehouse(Request $request){
        error_log('test');
        $term = trim($request->q);
        $warehouses = Warehouse::where('name', 'LIKE', '%'. $term. '%')->get();

        error_log('test1');

        $formatted_tags = [];

        foreach ($warehouses as $warehouse) {
            $formatted_tags[] = ['id' => $warehouse->id, 'text' => $warehouse->name];
        }

        error_log('test2');

        return Response::json($formatted_tags);
    }
}