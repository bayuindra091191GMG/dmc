<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 01/02/2018
 * Time: 15:45
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\MachineryBrand;
use App\Transformer\MasterData\MachineryBrandTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class MachineryBrandController extends Controller
{
    public function index(){
        return View('admin.machinery_brands.index');
    }

    public function create(){
        return View('admin.machinery_brands.create');
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name'          => 'required|max:45',
            'code'          => 'max:45',
            'description'   => 'max:200'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors())->withInput($request->all());

        $machineryBrand = MachineryBrand::create([
            'name'          => Input::get('name')
        ]);

        if(!empty(Input::get('code'))) $machineryBrand->code = Input::get('code');
        if(!empty(Input::get('description'))) $machineryBrand->description = Input::get('description');
        $machineryBrand->save();

        Session::flash('message', 'Berhasil membuat data merek alat berat baru!');

        return redirect()->route('admin.machinery_brands');
    }

    public function edit(MachineryBrand $machineryBrand){
        return View('admin.machinery_brands.edit',['machineryBrand' => $machineryBrand]);
    }

    public function update(Request $request, MachineryBrand $machineryBrand){
        $validator = Validator::make($request->all(), [
            'name'          => 'required|max:45',
            'code'          => 'max:45',
            'description'   => 'max:200'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());

        $machineryBrand->name = Input::get('name');
        $machineryBrand->code = Input::get('code');
        $machineryBrand->description = Input::get('description');

        $machineryBrand->save();

        Session::flash('message', 'Berhasil mengubah data merek alat berat!');

        return redirect()->route('admin.machinery_brands.edit', ['machinery_brand' => $machineryBrand]);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getIndex()
    {
        $machineryBrands = MachineryBrand::all();
        return DataTables::of($machineryBrands)
            ->setTransformer(new MachineryBrandTransformer())
            ->addIndexColumn()
            ->make(true);
    }
}