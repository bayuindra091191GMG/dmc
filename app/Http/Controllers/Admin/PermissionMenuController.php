<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Auth\Role\Role;
use App\Models\Menu;
use App\Models\PermissionMenu;
use App\Transformer\MasterData\PermissionMenuTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Session;
use Validator;

class PermissionMenuController extends Controller
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
        return view('admin.permission_menus.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $roles = Role::all();
        $menus = Menu::all();
        return view('admin.permission_menus.create', compact('menus', 'roles'));
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
            'role' => 'required',
            'menu' => 'required',
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());

        //Checking
        $role_id = $request->get('role');
        $menu_id = $request->get('menu');

        $permission = PermissionMenu::where('role_id', $role_id)->where('menu_id', $menu_id)->first();
        if($permission != null){
            Session::flash('error', 'Otorisasi Menu Sudah Dibuat!');
            return redirect(route('admin.permission_menus.create'));
        }

        $dateTimeNow = Carbon::now('Asia/Jakarta');
        $user = Auth::user();

        PermissionMenu::create([
            'role_id'       => $role_id,
            'menu_id'       => $menu_id,
            'created_by'    => $user->id,
            'created_at'    => $dateTimeNow->toDateTimeString(),
            'updated_by'    => $user->id,
            'updated_at'    => $dateTimeNow->toDateTimeString()
        ]);

        Session::flash('message', 'Sukses membuat Otorisasi Menu Baru!');

        return redirect(route('admin.permission_menus.create'));
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
    public function edit($id)
    {
        //
        $permissionMenu = PermissionMenu::find($id);
        $menus = Menu::all();
        $roles = Role::all();

        return view('admin.permission_menus.edit', compact('menus', 'roles', 'permissionMenu'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $validator = Validator::make($request->all(), [
            'role' => 'required',
            'menu' => 'required',
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());
        $dateTimeNow = Carbon::now('Asia/Jakarta');
        $user = Auth::user();
        $role_id = $request->get('role');
        $menu_id = $request->get('menu');

        $permissionMenu = PermissionMenu::find($id);
        $permissionMenu->role_id = $role_id;
        $permissionMenu->menu_id = $menu_id;
        $permissionMenu->updated_by = $user->id;
        $permissionMenu->updated_at = $dateTimeNow->toDateTimeString();
        $permissionMenu->save();

        Session::flash('message', 'Sukses mengubah data Otorisasi Menu!');

        return redirect(route('admin.permission_menus.edit', ['permission_menu' => $permissionMenu->id]));
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

    public function getIndex()
    {
        $permissionMenus = PermissionMenu::all();
        return DataTables::of($permissionMenus)
            ->setTransformer(new PermissionMenuTransformer())
            ->addIndexColumn()
            ->make(true);
    }
}
