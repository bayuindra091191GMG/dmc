<?php

namespace App\Http\Controllers\Admin;

use App\Models\Auth\Role\Role;
use App\Models\Auth\User\User;
use App\Models\Department;
use App\Transformer\MasterData\DepartmentTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('admin.departments.index');
    }

    //DataTables

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function getIndex()
    {
        $departments = Department::codeAscending()->get();
        return DataTables::of($departments)
            ->setTransformer(new DepartmentTransformer)
            ->addIndexColumn()
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.departments.create');
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
            'code' => 'required|max:30|regex:/^\S*$/u|unique:departments',
            'name' => 'required|max:45'
        ],[
            'code.unique'   => 'Kode departemen telah terpakai',
            'code.regex'    => 'Kode departemen harus tanpa spasi'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors())->withInput($request->all());

        $dateTimeNow = Carbon::now('Asia/Jakarta');

        $department = Department::create([
            'code'          => $request->input('code'),
            'name'          => $request->input('name'),
            'updated_by'    => 1,
            'created_by'    => 1,
            'created_at'    => $dateTimeNow->toDateTimeString()
        ]);

        Session::flash('message', 'Berhasil membuat data departemen baru!');

        return redirect()->route('admin.departments');
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
//    public function show(User $user)
//    {
//        return view('admin.users.show', ['user' => $user]);
//    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Department $department
     * @return \Illuminate\Http\Response
     */
    public function edit(Department $department)
    {
        return view('admin.departments.edit', ['department' => $department]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Department $department
     * @return mixed
     */
    public function update(Request $request, Department $department)
    {
        $id = $department->id;

        $validator = Validator::make($request->all(), [
            'code' => [
                'required',
                'max:30',
                'regex:/^\S*$/u',
                Rule::unique('departments')->ignore($department->id)
            ],
            'name' => 'required|max:45'
        ],[
            'code.unique'   => 'Kode departemen telah terpakai!',
            'code.regex'    => 'Kode departemen harus tanpa spasi'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());

        $dateTimeNow = Carbon::now('Asia/Jakarta');

        $department->name = $request->input('name');
        $department->code = $request->input('code');
        $department->updated_at = $dateTimeNow->toDateTimeString();
        $department->updated_by = 1;

        $department->save();

//        return redirect()->intended(route('admin.departments'));
        Session::flash('message', 'Berhasil mengubah data departemen!');

        return redirect()->route('admin.departments.edit', ['department' => $department]);
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
            $department = Department::find($request->input('id'));
            $department->delete();

            Session::flash('message', 'Berhasil menghapus data departemen '. $department->name);
            return Response::json(array('success' => 'VALID'));
        }
        catch(\Exception $ex){
            return Response::json(array('errors' => 'INVALID'));
        }
    }
}
