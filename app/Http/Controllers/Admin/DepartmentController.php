<?php

namespace App\Http\Controllers\Admin;

use App\Models\Auth\Role\Role;
use App\Models\Auth\User\User;
use App\Models\Department;
use App\Transformer\MasterData\DepartmentTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
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
     */
    public function anyData()
    {
        $departments = Department::all();
        return DataTables::of($departments)
            ->setTransformer(new DepartmentTransformer())
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
            'code' => 'required|max:45',
            'name' => 'required|max:45'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
        $dateTimeNow = Carbon::now('Asia/Jakarta');

        $department = Department::create([
            'code'          => $request->get('code'),
            'name'          => $request->get('name'),
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
        $validator = Validator::make($request->all(), [
            'code' => 'required|max:45',
            'name' => 'required|max:45'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());

        $dateTimeNow = Carbon::now('Asia/Jakarta');

        $department->name = $request->get('name');
        $department->code = $request->get('code');
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
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
