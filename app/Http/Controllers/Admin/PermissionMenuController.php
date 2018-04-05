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
use Illuminate\Support\Facades\Input;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

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
            'role' => 'required'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());

        //Checking
        if($request->input('role') === '-1'){
            return redirect()->back()->withErrors('Pilih Role!', 'default')->withInput($request->all());
        }

        $menus = Input::get('ids');
        $role_id = $request->get('role');
        $exist = true;
        $valid = true;

        foreach ($menus as $menu){
            if(empty($menu)) $exist = false;
            $permission = PermissionMenu::where('role_id', $role_id)->where('menu_id', $menu)->first();
            if($permission != null){
                $valid = false;
            }
        }

        if(!$exist){
            return redirect()->back()->withErrors('Belum ada menu yang dipilih!', 'default')->withInput($request->all());
        }

        if(!$valid){
            return redirect()->back()->withErrors('Otorisasi Menu Sudah Dibuat!', 'default')->withInput($request->all());
        }

        $dateTimeNow = Carbon::now('Asia/Jakarta');
        $user = Auth::user();

        foreach ($menus as $menu){
            PermissionMenu::create([
                'role_id'       => $role_id,
                'menu_id'       => $menu,
                'created_by'    => $user->id,
                'created_at'    => $dateTimeNow->toDateTimeString(),
                'updated_by'    => $user->id,
                'updated_at'    => $dateTimeNow->toDateTimeString()
            ]);
        }

        Session::flash('message', 'Berhasil membuat data otorisasi menu baru!');
        return redirect(route('admin.permission_menus'));
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
     * @param PermissionMenu $permissionMenu
     * @return \Illuminate\Http\Response
     */
    public function edit(PermissionMenu $permissionMenu)
    {
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
        //On Progress Editing the Permissions
//        //
//        $validator = Validator::make($request->all(), [
//            'role' => 'required'
//        ]);
//
//        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());
//
//        $dateTimeNow = Carbon::now('Asia/Jakarta');
//        $user = Auth::user();
//        $role_id = $request->get('role');
//        $menu_id = $request->get('menu');
//
//        //Checking
//        $menus = Input::get('ids');
//        $role_id = $request->get('role');
//        $exist = true;
//
//        foreach ($menus as $menu){
//            if(empty($item)) $exist = false;
//        }
//
//        if(!$exist){
//            return redirect()->back()->withErrors('Belum ada menu yang dipilih!', 'default')->withInput($request->all());
//        }
//
//        $allPermission = PermissionMenu::where('role_id', $role_id)->get();
//
//        foreach ($menus as $menu){
//            $permissionMenu = PermissionMenu::find($id);
//
//            if($permissionMenu == null){
//                PermissionMenu::create([
//                    'role_id'       => $role_id,
//                    'menu_id'       => $menu,
//                    'created_by'    => $user->id,
//                    'created_at'    => $dateTimeNow->toDateTimeString(),
//                    'updated_by'    => $user->id,
//                    'updated_at'    => $dateTimeNow->toDateTimeString()
//                ]);
//            }
//            else{
//
//                $permissionMenu->role_id = $role_id;
//                $permissionMenu->menu_id = $menu_id;
//                $permissionMenu->updated_by = $user->id;
//                $permissionMenu->updated_at = $dateTimeNow->toDateTimeString();
//                $permissionMenu->save();
//            }
//        }
//
//        Session::flash('message', 'Sukses mengubah data Otorisasi Menu!');
//
//        return redirect(route('admin.permission_menus.edit', ['permission_menu' => $permissionMenu->id]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $permission = PermissionMenu::find($id);
        $permission->delete();

        Session::flash('message', 'Sukses menghapus data Otorisasi Menu!');
        return view('admin.permission_menus.index');
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
