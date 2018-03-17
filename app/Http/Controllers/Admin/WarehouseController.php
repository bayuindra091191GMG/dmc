<?php

namespace App\Http\Controllers\Admin;

use App\Models\Auth\Role\Role;
use App\Models\Auth\User\User;
use App\Models\Site;
use App\Models\Warehouse;
use App\Transformer\MasterData\WarehouseTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('admin.warehouses.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sites = Site::all();

        return view('admin.warehouses.create', compact('sites'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code'      => 'required|max:30|regex:/^\S*$/u|unique:warehouses',
            'name'      => 'required|max:45',
            'phone'     => 'max:20'
        ],[
            'code.unique'   => 'Kode gudang telah terpakai!',
            'code.regex'    => 'Kode gudang harus tanpa spasi'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors())->withInput($request->all());

        if($request->input('site') == '-1'){
            return redirect()->back()->withErrors('Pilih Site!', 'default')->withInput($request->all());
        }

        $warehouse = Warehouse::create([
            'code'          => $request->input('code'),
            'name'          => $request->input('name'),
            'site_id'       => $request->input('site'),
            'phone'         => $request->input('phone'),
        ]);

        Session::flash('message', 'Berhasil membuat data gudang unit!');

        return redirect()->route('admin.warehouses');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Warehouse $group
     * @return \Illuminate\Http\Response
     */
    public function edit(Warehouse $warehouse)
    {
        $sites = Site::all();

        $data = [
            'warehouse'     => $warehouse,
            'sites'         => $sites
        ];

        return view('admin.warehouses.edit')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Warehouse $warehouse
     * @return mixed
     */
    public function update(Request $request, Warehouse $warehouse)
    {
        $validator = Validator::make($request->all(), [
            'code'      => [
                'required',
                'max:30',
                'regex:/^\S*$/u',
                Rule::unique('warehouses')->ignore($warehouse->id)
            ],
            'name'      => 'required|max:45',
            'phone'     => 'max:20'
        ],[
            'code.unique'   => 'Kode gudang telah terpakai!',
            'code.regex'    => 'Kode gudang harus tanpa spasi'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());

        $warehouse->name = $request->input('name');
        $warehouse->code = $request->input('code');
        $warehouse->site_id = $request->input('site');
        $warehouse->phone = $request->input('phone');

        $warehouse->save();

//        return redirect()->intended(route('admin.warehouses'));
        Session::flash('message', 'Berhasil mengubah data gudang!');

        return redirect()->route('admin.warehouses');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        try{
            $warehouse = Warehouse::find($request->input('id'));
            $warehouse->delete();

            Session::flash('message', 'Berhasil menghapus data gudang '. $warehouse->name);
            return Response::json(array('success' => 'VALID'));
        }
        catch(\Exception $ex){
            return Response::json(array('errors' => 'INVALID'));
        }
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function getIndex()
    {
        $warehouses= Warehouse::where('id', '>', 0)->get();
        return DataTables::of($warehouses)
            ->setTransformer(new WarehouseTransformer)
            ->addIndexColumn()
            ->make(true);
    }

    public function getWarehouses(Request $request){
        $term = trim($request->q);
        $warehouses = Warehouse::where('name', 'LIKE', '%'. $term. '%')
            ->where('id', '>', 0)->get();

        $formatted_tags = [];

        foreach ($warehouses as $warehouse) {
            $formatted_tags[] = ['id' => $warehouse->id, 'text' => $warehouse->name];
        }

        return Response::json($formatted_tags);
    }

    public function getExtendedWarehouses(Request $request){
        $term = trim($request->q);
        $warehouses = Warehouse::where('name', 'LIKE', '%'. $term. '%')
            ->where('id', '>', 0)->get();

        $formatted_tags = [];

        foreach ($warehouses as $warehouse) {
            $formatted_tags[] = ['id' => $warehouse->id, 'text' => 'SITE: '. $warehouse->site->name. ' - GUDANG: '. $warehouse->name];
        }

        return Response::json($formatted_tags);
    }
}
