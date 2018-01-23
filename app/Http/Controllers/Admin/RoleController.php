<?php

namespace App\Http\Controllers\Admin;

use App\Models\Auth\Role\Role;
use Arcanedev\LogViewer\Entities\Log;
use Arcanedev\LogViewer\Entities\LogEntry;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Validator;
use Yajra\DataTables\DataTables;

class RoleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('admin.roles.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'description' => 'required|max:255'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());
        $dateTimeNow = Carbon::now('Asia/Jakarta');

        $user = Auth::guard()->user();

        Role::create([
            'name'          => $request->get('name'),
            'description'   => $request->get('description'),
            'updated_by'    => $user->id,
            'created_by'    => $user->id,
            'created_at'    => $dateTimeNow->toDateTimeString()
        ]);

        Session::flash('message', 'Sukses membuat Role Baru!');

        return redirect()->intended(route('admin.role.create'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        //
        return view('admin.roles.edit', ['role' => $role, 'roles' => Role::get()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Role $role
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function update(Request $request, Role $role)
    {
        //
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'description' => 'required|max:255',
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());

        $dateTimeNow = Carbon::now('Asia/Jakarta');

        $user = Auth::guard()->user();

        $role->name = $request->get('name');
        $role->description = $request->get('description');
        $role->updated_by = $user->id;
        $role->updated_at = $dateTimeNow->toDateTimeString();

        $role->save();

        Session::flash('message', 'Sukses Mengubah Data!');

        return redirect()->intended(route('admin.roles.edit', ['role' => $role->id]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIndex()
    {
        $roles = Role::all();
        return DataTables::of($roles)
            ->addColumn('action', function ($role) {
                return "<a class='btn btn-xs btn-info' href='roles/".$role->id."/ubah' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";
            })->make(true);
    }
}
