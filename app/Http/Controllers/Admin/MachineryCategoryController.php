<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 01/02/2018
 * Time: 15:00
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\MachineryCategory;
use App\Transformer\MasterData\MachineryCategoryTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class MachineryCategoryController extends Controller
{
    public function index(){
        return View('admin.machinery_categories.index');
    }

    public function create(){
        return View('admin.machinery_categories.create');
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name'          => 'required|max:45',
            'code'          => 'max:45',
            'description'   => 'max:200'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors())->withInput($request->all());

        $machineryCategory = MachineryCategory::create([
            'name'          => Input::get('name')
        ]);

        if(!empty(Input::get('code'))) $machineryCategory->code = Input::get('code');
        if(!empty(Input::get('description'))) $machineryCategory->description = Input::get('description');
        $machineryCategory->save();

        Session::flash('message', 'Berhasil membuat data kategori alat berat baru!');

        return redirect()->route('admin.machinery_categories.create');
    }

    public function edit(MachineryCategory $machineryCategory){
        return view('admin.machinery_categories.edit', ['machineryCategory' => $machineryCategory]);
    }

    public function update(Request $request, MachineryCategory $machineryCategory){
        $validator = Validator::make($request->all(), [
            'name'          => 'required|max:45',
            'code'          => 'max:45',
            'description'   => 'max:200'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors())->withInput($request->all());

        $machineryCategory->name = Input::get('name');
        $machineryCategory->code = Input::get('code');
        $machineryCategory->description = Input::get('description');
        $machineryCategory->save();

        Session::flash('message', 'Berhasil mengubah data kategori alat berat!');

        return redirect()->route('admin.machinery_categories.edit', ['machineryCategory' => $machineryCategory]);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getIndex(){
        $machineryCategory = MachineryCategory::all();
        return DataTables::of($machineryCategory)
            ->setTransformer(new MachineryCategoryTransformer())
            ->addIndexColumn()
            ->make(true);
    }
}