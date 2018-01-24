<?php

namespace App\Http\Controllers\Admin;

use App\Models\Auth\Role\Role;
use App\Models\Auth\User\User;
use App\Models\Machinery;
use App\Models\MachineryType;
use App\Transformer\MasterData\MachineryTypeTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class MachineryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('admin.machineries.index');
    }

    //DataTables
    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function anyData()
    {
        $machineries = Machinery::all();
        return DataTables::of($machineries)
            ->setTransformer(new MachineryTypeTransformer())
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
        return view('admin.machineries.create', ['machinery_types' => MachineryType::all()]);
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
            'machinery_type' => 'required'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
        $dateTimeNow = Carbon::now('Asia/Jakarta');

        $machinery = Machinery::create([
            'code'          => $request->get('code'),
            'machinery_type_id'          => $request->get('machinery_type'),
            'updated_by'    => 1,
            'created_by'    => 1,
            'created_at'    => $dateTimeNow->toDateTimeString()
        ]);

//        return redirect()->intended(route('admin.machineries'));
        Session::flash('message', 'Berhasil membuat data alat berat baru!');

        return redirect()->route('admin.machineries.create', ['machinery_types' => MachineryType::all()]);
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
     * @param Machinery $machinery
     * @return \Illuminate\Http\Response
     */
    public function edit(Machinery $machinery)
    {
        return view('admin.machineries.edit', ['machinery' => $machinery]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Machinery $machinery
     * @return mixed
     */
    public function update(Request $request, Machinery $machinery)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|max:45',
            'machinery_type' => 'required'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());

        $dateTimeNow = Carbon::now('Asia/Jakarta');

        $machinery->machinery_type_id = $request->get('machinery_type');
        $machinery->code = $request->get('code');
        $machinery->updated_at = $dateTimeNow->toDateTimeString();
        $machinery->updated_by = 1;

        $machinery->save();

//        return redirect()->intended(route('admin.machineries'));
        Session::flash('message', 'Berhasil mengubah data golongan!');

        return redirect()->route('admin.machineries.edit', ['machinery' => $machinery]);
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
