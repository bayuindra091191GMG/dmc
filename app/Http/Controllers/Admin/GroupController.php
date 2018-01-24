<?php

namespace App\Http\Controllers\Admin;

use App\Models\Auth\Role\Role;
use App\Models\Auth\User\User;
use App\Models\Group;
use App\Transformer\MasterData\GroupTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('admin.groups.index');
    }

    //DataTables
    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function anyData()
    {
        $groups = Group::all();
        return DataTables::of($groups)
            ->setTransformer(new GroupTransformer())
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.groups.create');
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

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());
        $dateTimeNow = Carbon::now('Asia/Jakarta');

        $group = Group::create([
            'code'          => $request->get('code'),
            'name'          => $request->get('name'),
            'updated_by'    => 1,
            'created_by'    => 1,
            'created_at'    => $dateTimeNow->toDateTimeString()
        ]);

//        return redirect()->intended(route('admin.groups'));
        Session::flash('message', 'Berhasil membuat data golongan baru!');

        return redirect()->route('admin.groups.create');
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
     * @param Group $group
     * @return \Illuminate\Http\Response
     */
    public function edit(Group $group)
    {
        return view('admin.groups.edit', ['group' => $group]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Group $group
     * @return mixed
     */
    public function update(Request $request, Group $group)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|max:45',
            'name' => 'required|max:45'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());

        $dateTimeNow = Carbon::now('Asia/Jakarta');

        $group->name = $request->get('name');
        $group->code = $request->get('code');
        $group->updated_at = $dateTimeNow->toDateTimeString();
        $group->updated_by = 1;

        $group->save();

//        return redirect()->intended(route('admin.groups'));
        Session::flash('message', 'Berhasil mengubah data golongan!');

        return redirect()->route('admin.groups.edit', ['group' => $group]);
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
