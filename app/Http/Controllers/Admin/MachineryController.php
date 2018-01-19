<?php

namespace App\Http\Controllers\Admin;

use App\Models\Auth\Role\Role;
use App\Models\Auth\User\User;
use App\Models\Machinery;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Validator;

class MachineryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('admin.machineries.index', ['machineries' => Machinery::all()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.machineries.create');
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

        $group = Machinery::create([
            'code'          => $request->get('code'),
            'name'          => $request->get('name'),
            'updated_by'    => 1,
            'created_by'    => 1,
            'created_at'    => $dateTimeNow->toDateTimeString()
        ]);

        return redirect()->intended(route('admin.machineries'));
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
     * @param Machinery $group
     * @return \Illuminate\Http\Response
     */
    public function edit(Machinery $group)
    {
        return view('admin.machineries.edit', ['group' => $group]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Machinery $group
     * @return mixed
     */
    public function update(Request $request, Machinery $group)
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

        return redirect()->intended(route('admin.machineries'));
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
