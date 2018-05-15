<?php

namespace App\Http\Controllers\Admin;

use App\Models\Auth\Role\Role;
use App\Models\Auth\User\User;
use App\Models\Menu;
use App\Models\MenuHeader;
use App\Models\MenuSub;
use App\Transformer\MasterData\MenuHeaderTransformer;
use App\Transformer\MasterData\MenuTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class MenuHeaderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('admin.menu_headers.index');
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
        $menus = MenuHeader::all();
        return DataTables::of($menus)
            ->setTransformer(new MenuHeaderTransformer())
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
        return view('admin.menu_headers.create');
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
            'name'  => 'required|max:50'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors())->withInput($request->all());

        MenuHeader::create([
            'name'  => $request->get('name')
        ]);

        Session::flash('message', 'Berhasil membuat data menu header baru!');

        return redirect()->route('admin.menu_headers');
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
     * @param MenuHeader $menuHeader
     * @return \Illuminate\Http\Response
     */
    public function edit(MenuHeader $menuHeader)
    {
        return view('admin.menu_headers.edit', ['menuHeader' => $menuHeader]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param MenuHeader $menuHeader
     * @return mixed
     * @internal param Menu $machinery_type
     */
    public function update(Request $request, MenuHeader $menuHeader)
    {
        $validator = Validator::make($request->all(), [
            'name'              => 'required|max:50'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());

        $menuHeader->name = $request->get('name');
        $menuHeader->save();

//        return redirect()->intended(route('admin.menus'));
        Session::flash('message', 'Berhasil mengubah data menu header!');

        return redirect()->route('admin.menu_headers.edit', ['menuHeader' => $menuHeader]);
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
            $menuHeader = MenuHeader::find($request->input('id'));
            $menus = Menu::where('menu_header_id', $menuHeader->id)->get();

            if($menus != null || $menus->count() > 0) {
                foreach ($menus as $menu) {
                    //Deleting all the Sub Menus
                    MenuSub::where('menu_id', $menu->id)->delete();
                }

                //Delete all Menu from the Header Menu
                Menu::where('menu_header_id', $menuHeader->id)->delete();
            }

            //Delete header Menu
            $menuHeader->delete();

            Session::flash('message', 'Berhasil menghapus data Menu Header '. $menuHeader->name);
            return Response::json(array('success' => 'VALID'));
        }
        catch(\Exception $ex){
            return Response::json(array('errors' => 'INVALID'));
        }
    }
}
