<?php

namespace App\Http\Controllers\Admin;

use App\Models\Auth\Role\Role;
use App\Models\Auth\User\User;
use App\Models\Group;
use App\Transformer\MasterData\GroupTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.groups.index');
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
        $groups = Group::all();
        return DataTables::of($groups)
            ->setTransformer(new GroupTransformer)
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
            'code' => 'required|max:30|unique:groups|regex:/^\S*$/u',
            'name' => 'required|max:45'
        ],[
            'code.unique'   => 'Kode kategori inventory telah terpakai',
            'code.regex'    => 'Kode kategori inventory harus tanpa spasi'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors())->withInput($request->all());

        $group = Group::create([
            'code'          => $request->input('code'),
            'name'          => $request->input('name')
        ]);

        Session::flash('message', 'Berhasil membuat data kategori inventory baru!');

        return redirect()->route('admin.groups');
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
            'code'          => [
                'required',
                'max:30',
                'regex:/^\S*$/u',
                Rule::unique('groups')->ignore($group->id)
            ],
            'name'          => 'required|max:45'
        ],[
            'code.unique'   => 'Kode kategori inventory telah terpakai',
            'code.regex'    => 'Kode kategori inventory harus tanpa spasi'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());

        $group->name = $request->input('name');
        $group->code = $request->input('code');

        $group->save();

        Session::flash('message', 'Berhasil mengubah data kategori inventory!');

        return redirect()->route('admin.groups.edit', ['group' => $group]);
    }

    public function destroy(Request $request)
    {
        try{
            $group = Group::find($request->input('id'));
            $group->delete();

            Session::flash('message', 'Berhasil menghapus data kategori inventory '. $group->name);
            return Response::json(array('success' => 'VALID'));
        }
        catch(\Exception $ex){
            return Response::json(array('errors' => 'INVALID'));
        }
    }

    public function getGroups(Request $request){
        $term = trim($request->q);
        $groups = Group::where('name', 'LIKE', '%'. $term. '%')->get();

        $formatted_tags = [];

        foreach ($groups as $group) {
            $formatted_tags[] = ['id' => $group->id, 'text' => $group->name];
        }

        return Response::json($formatted_tags);
    }
}
