<?php

namespace App\Http\Controllers\Admin;

use App\Models\Auth\Role\Role;
use App\Models\Auth\User\User;
use App\Models\MachineryType;
use App\Transformer\MasterData\MachineryTypeTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class MachineryTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('admin.machinery_types.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.machinery_types.create');
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
            'name'          => 'required|max:45',
            'code'          => 'max:45',
            'description'   => 'max:200'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors())->withInput($request->all());

        $machineryType = MachineryType::create([
            'name'          => Input::get('name')
        ]);

        if(!empty(Input::get('code'))) $machineryType->code = Input::get('code');
        if(!empty(Input::get('description'))) $machineryType->description = Input::get('description');
        $machineryType->save();

        Session::flash('message', 'Berhasil membuat data tipe alat berat baru!');

        return redirect()->route('admin.machinery_types.create');
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
     * @param MachineryType $machineryType
     * @return \Illuminate\Http\Response
     */
    public function edit(MachineryType $machineryType)
    {
        return view('admin.machinery_types.edit', ['machineryType' => $machineryType]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param MachineryType $machineryType
     * @return mixed
     */
    public function update(Request $request, MachineryType $machineryType)
    {
        $validator = Validator::make($request->all(), [
            'name'          => 'required|max:45',
            'code'          => 'max:45',
            'description'   => 'max:200'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());

        $machineryType->name = Input::get('name');
        $machineryType->code = Input::get('code');
        $machineryType->description = Input::get('description');

        $machineryType->save();

        Session::flash('message', 'Berhasil mengubah data tipe alat berat!');

        return redirect()->route('admin.machinery_types.edit', ['machinery_type' => $machineryType]);
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

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function getIndex()
    {
        $machinery_types = MachineryType::all();
        return DataTables::of($machinery_types)
            ->setTransformer(new MachineryTypeTransformer())
            ->addIndexColumn()
            ->make(true);
    }

    public function getMachineryTypes(Request $request){
        $term = trim($request->q);
        $groups = MachineryType::where('name', 'LIKE', '%'. $term. '%')->get();

        $formatted_tags = [];

        foreach ($groups as $group) {
            $formatted_tags[] = ['id' => $group->id, 'text' => $group->name];
        }

        return Response::json($formatted_tags);
    }
}
