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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
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
            'description'   => 'required|max:45'
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

    public function edit($status){
        $statusEdit = Status::all();

        $data = [
            'status'    => $statusEdit
        ];

        return View('admin.statuses.create')->with($data);
    }

    public function update(Request $request, Status $status){
        $validator = Validator::make($request->all(), [
            'description'   => 'required|max:45'
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

    public function getIndex(){
        $statuses = Status::all();
        return DataTables::of($statuses)
            ->addColumn('action', function ($status){
                return "<a class='btn btn-xs btn-info' href='statuses/".$status->id."/ubah' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";
            })
            ->addIndexColumn()
            ->make(true);
    }
}