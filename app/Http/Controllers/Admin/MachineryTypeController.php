<?php

namespace App\Http\Controllers\Admin;

use App\Models\Auth\Role\Role;
use App\Models\Auth\User\User;
use App\Models\MachineryType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Validator;

class MachineryTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('admin.machinery_types.index', ['machinery_types' => MachineryType::all()]);
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
            'description' => 'required|max:50'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());
        $dateTimeNow = Carbon::now('Asia/Jakarta');

        $group = MachineryType::create([
            'description'          => $request->get('description'),
        ]);

        return redirect()->intended(route('admin.machinery_types'));
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
     * @param MachineryType $machinery_type
     * @return \Illuminate\Http\Response
     */
    public function edit(MachineryType $machinery_type)
    {
        return view('admin.machinery_types.edit', ['machinery_type' => $machinery_type]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param MachineryType $machinery_type
     * @return mixed
     */
    public function update(Request $request, MachineryType $machinery_type)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'required|max:50'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());

        $dateTimeNow = Carbon::now('Asia/Jakarta');

        $machinery_type->description = $request->get('description');

        $machinery_type->save();

        return redirect()->intended(route('admin.machinery_types'));
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
