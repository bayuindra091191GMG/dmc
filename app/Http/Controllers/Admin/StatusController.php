<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 24/01/2018
 * Time: 14:34
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Status;
use App\Transformer\MasterData\StatusTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class StatusController extends Controller
{
    public function index(){
        return View('admin.statuses.index');
    }

    public function create(){
        return View('admin.statuses.create');
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'description'   => 'required|max:45|unique:statuses'
        ],[
            'description.unique'   => 'Status telah terpakai'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $status = Status::create([
            'description'   => Input::get('description')
        ]);

        Session::flash('message', 'Berhasil membuat data status baru!');

        return redirect()->route('admin.statuses');
    }

    public function edit(Status $status){
        return View('admin.statuses.edit', compact('status'));
    }

    public function update(Request $request, Status $status){
        $validator = Validator::make($request->all(), [
            'description' => [
                'required',
                'max:45',
                Rule::unique('statuses')->ignore($status->id)
            ]
        ],[
            'description.unique'   => 'Status telah terpakai'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $status->description = Input::get('description');

        Session::flash('message', 'Berhasil mengubah data status!');

        return redirect()->route('admin.statuses.edit');
    }

    public function destroy(Request $request)
    {
        try{
            $status = Status::find($request->input('id'));
            $status->delete();

            Session::flash('message', 'Berhasil menghapus data status '. $status->description);
            return Response::json(array('success' => 'VALID'));
        }
        catch(\Exception $ex){
            return Response::json(array('errors' => 'INVALID'));
        }
    }

    public function getIndex(){
        $statuses = Status::all();
        return DataTables::of($statuses)
            ->setTransformer(new StatusTransformer)
            ->make(true);
    }
}