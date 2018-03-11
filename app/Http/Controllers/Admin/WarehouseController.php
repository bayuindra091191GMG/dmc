<?php

namespace App\Http\Controllers\Admin;

use App\Models\Auth\Role\Role;
use App\Models\Auth\User\User;
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
        return view('admin.warehouses.create');
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
            'code' => 'required|max:30|regex:/^\S*$/u|unique:warehouses',
            'name' => 'required|max:45'
        ],[
            'code.unique'   => 'Kode gudang telah terpakai!',
            'code.regex'    => 'Kode gudang harus tanpa spasi'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
        $dateTimeNow = Carbon::now('Asia/Jakarta');

        $user = Auth::user();

        $warehouse = Warehouse::create([
            'code'          => $request->input('code'),
            'name'          => $request->input('name'),
            'location'      => $request->input('location'),
            'phone'         => $request->input('phone'),
            'created_by'    => $user->id,
            'created_at'    => $dateTimeNow->toDateTimeString()
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
        return view('admin.warehouses.edit', ['warehouse' => $warehouse]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Warehouse $group
     * @return mixed
     */
    public function update(Request $request, Warehouse $warehouse)
    {
        $validator = Validator::make($request->all(), [
            'code' => [
                'required',
                'max:30',
                'regex:/^\S*$/u',
                Rule::unique('warehouses')->ignore($warehouse->id)
            ],
            'name' => 'required|max:45'
        ],[
            'code.unique'   => 'Kode gudang telah terpakai!',
            'code.regex'    => 'Kode gudang harus tanpa spasi'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());

        $dateTimeNow = Carbon::now('Asia/Jakarta');

        $warehouse->name = $request->input('name');
        $warehouse->code = $request->input('code');
        $warehouse->location = $request->input('location');
        $warehouse->phone = $request->input('phone');
        $warehouse->updated_at = $dateTimeNow->toDateTimeString();
        $warehouse->updated_by = 1;

        $warehouse->save();

//        return redirect()->intended(route('admin.warehouses'));
        Session::flash('message', 'Berhasil mengubah data gudang!');

        return redirect()->route('admin.warehouses.edit', ['warehouse' => $warehouse]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
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
     */
    public function getIndex()
    {
        $warehouses= Warehouse::all();
        return DataTables::of($warehouses)
            ->setTransformer(new WarehouseTransformer)
            ->addIndexColumn()
            ->make(true);
    }
}
