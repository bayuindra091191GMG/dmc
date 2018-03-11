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
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
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
            'code'          => 'required|max:30|regex:/^\S*$/u|unique:machinery_categories',
            'name'          => 'required|max:45',
            'description'   => 'max:200'
        ],[
            'code.unique'   => 'Kode kategori alat berat telah terpakai!',
            'code.regex'    => 'Kode kategori alat berat harus tanpa spasi'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors())->withInput($request->all());

        $machineryCategory = MachineryCategory::create([
            'name'          => Input::get('name')
        ]);

        if(!empty(Input::get('code'))) $machineryCategory->code = Input::get('code');
        if(!empty(Input::get('description'))) $machineryCategory->description = Input::get('description');
        $machineryCategory->save();

        Session::flash('message', 'Berhasil membuat data kategori alat berat baru!');

        return redirect()->route('admin.machinery_categories');
    }

    public function edit(MachineryCategory $machineryCategory){
        return view('admin.machinery_categories.edit', ['machineryCategory' => $machineryCategory]);
    }

    public function update(Request $request, MachineryCategory $machineryCategory){
        $validator = Validator::make($request->all(), [
            'code' => [
                'required',
                'max:30',
                'regex:/^\S*$/u',
                Rule::unique('machinery_categories')->ignore($machineryCategory->id)
            ],
            'name'          => 'required|max:45',
            'description'   => 'max:200'
        ],[
            'code.unique'   => 'Kode kategori alat berat telah terpakai!',
            'code.regex'    => 'Kode kategori alat berat harus tanpa spasi'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors())->withInput($request->all());

        $machineryCategory->name = Input::get('name');
        $machineryCategory->code = Input::get('code');
        $machineryCategory->description = Input::get('description');
        $machineryCategory->save();

        Session::flash('message', 'Berhasil mengubah data kategori alat berat!');

        return redirect()->route('admin.machinery_categories.edit', ['machineryCategory' => $machineryCategory]);
    }

    public function destroy(Request $request)
    {
        try{
            $machineryCategory = MachineryCategory::find($request->input('id'));
            $machineryCategory->delete();

            Session::flash('message', 'Berhasil menghapus data kategori alat berat '. $machineryCategory->name);
            return Response::json(array('success' => 'VALID'));
        }
        catch(\Exception $ex){
            return Response::json(array('errors' => 'INVALID'));
        }
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