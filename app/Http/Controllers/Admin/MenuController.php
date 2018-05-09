<?php

namespace App\Http\Controllers\Admin;

use App\Models\Auth\Role\Role;
use App\Models\Auth\User\User;
use App\Models\Menu;
use App\Models\MenuHeader;
use App\Models\MenuSub;
use App\Transformer\MasterData\MenuTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('admin.menus.index');
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
        $menus = Menu::all();
        return DataTables::of($menus)
            ->setTransformer(new MenuTransformer())
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
        $header = MenuHeader::all();
        return view('admin.menus.create', compact('header'));
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
            'name'              => 'required|max:50',
            'menu_header_id'    => 'required'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors())->withInput($request->all());

        Menu::create([
            'name'              => $request->get('name'),
            'menu_header_id'    => $request->get('menu_header_id'),
            'route'             => $request->get('route')
        ]);

        Session::flash('message', 'Berhasil membuat data menu baru!');

        return redirect()->route('admin.menus');
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
     * @param Menu $menu
     * @return \Illuminate\Http\Response
     */
    public function edit(Menu $menu)
    {
        $header = MenuHeader::all();
        return view('admin.menus.edit', ['menu' => $menu, 'header' => $header]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Menu $machinery_type
     * @return mixed
     */
    public function update(Request $request, Menu $menu)
    {
        $validator = Validator::make($request->all(), [
            'name'              => 'required|max:50',
            'menu_header_id'    => 'required',
            'route'             => 'required'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());

        $dateTimeNow = Carbon::now('Asia/Jakarta');

        $menu->name = $request->get('name');
        $menu->menu_header_id = $request->get('menu_header_id');
        $menu->route = $request->get('route');
        $menu->save();

//        return redirect()->intended(route('admin.menus'));
        Session::flash('message', 'Berhasil mengubah data menu!');

        return redirect()->route('admin.menus.edit', ['menu' => $menu]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function destroy(Request $request)
    {
        try{
            $menu = Menu::find($request->input('id'));

            //Deleting all the Sub Menus
            MenuSub::where('menu_id', $menu->id)->delete();
            $menu->delete();

            Session::flash('message', 'Berhasil menghapus data menu '. $menu->name);
            return Response::json(array('success' => 'VALID'));
        }
        catch(\Exception $ex){
            return Response::json(array('errors' => 'INVALID'));
        }
    }
}
