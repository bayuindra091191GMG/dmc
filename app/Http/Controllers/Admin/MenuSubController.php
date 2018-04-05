<?php

namespace App\Http\Controllers\Admin;

use App\Models\Auth\Role\Role;
use App\Models\Auth\User\User;
use App\Models\Menu;
use App\Models\MenuHeader;
use App\Models\MenuSub;
use App\Transformer\MasterData\MenuSubTransformer;
use App\Transformer\MasterData\MenuTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class MenuSubController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('admin.menu_subs.index');
    }


    //DataTables

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function anyData()
    {
        $menus = MenuSub::all();
        return DataTables::of($menus)
            ->setTransformer(new MenuSubTransformer())
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
        $header = Menu::all();
        return view('admin.menu_subs.create', compact('header'));
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
            'name'       => 'required|max:50',
            'menu_id'    => 'required'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors())->withInput($request->all());

        MenuSub::create([
            'name'      => $request->get('name'),
            'menu_id'   => $request->get('menu_id'),
            'route'     => $request->get('route')
        ]);

        Session::flash('message', 'Berhasil membuat data menu sub baru!');

        return redirect()->route('admin.menu_subs');
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
     * @param MenuSub $menuSub
     * @return \Illuminate\Http\Response
     * @internal param Menu $menu
     */
    public function edit(MenuSub $menuSub)
    {
        $header = Menu::all();
        return view('admin.menu_subs.edit', ['menuSub' => $menuSub, 'header' => $header]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Menu $machinery_type
     * @return mixed
     */
    public function update(Request $request, MenuSub $menuSub)
    {
        $validator = Validator::make($request->all(), [
            'name'       => 'required|max:50',
            'menu_id'    => 'required'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());

        $menuSub->name = $request->get('name');
        $menuSub->menu_id = $request->get('menu_id');
        $menuSub->route = $request->get('route');
        $menuSub->save();

//        return redirect()->intended(route('admin.menus'));
        Session::flash('message', 'Berhasil mengubah data menu sub!');

        return redirect()->route('admin.menu_subs.edit', ['menuSub' => $menuSub]);
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
