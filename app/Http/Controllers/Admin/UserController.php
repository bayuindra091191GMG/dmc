<?php

namespace App\Http\Controllers\Admin;

use App\Models\Auth\Role\Role;
use App\Models\Auth\User\User;
use App\Transformer\MasterData\UserTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('admin.users.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all();
        return View('admin.users.create', compact('roles'));
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
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users|max:255'
        ]);

//        $validator->sometimes('email', 'unique:users', function ($input) use ($user) {
//            return strtolower($input->email) != strtolower($user->email);
//        });

        $validator->sometimes('password', 'min:6|confirmed', function ($input) {
            return $input->password;
        });

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());

        $user = new User();
        $user->name = Input::get('name');
        $user->email = Input::get('email');
        if(!empty(Input::get('employee'))) $user->employee_id = Input::get('employee');

        if ($request->has('password')) {
            $user->password = bcrypt($request->get('password'));
            $user->save();
        }

        $user->roles()->attach($request->get('role'));

        Session::flash('message', 'Berhasil membuat data user baru!');

        return redirect()->route('admin.users');
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(User $user)
    {
        return view('admin.users.show', ['user' => $user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', ['user' => $user, 'roles' => Role::get()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param User $user
     * @return mixed
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'active' => 'sometimes|boolean',
            'confirmed' => 'sometimes|boolean',
        ]);

        $validator->sometimes('email', 'unique:users', function ($input) use ($user) {
            return strtolower($input->email) != strtolower($user->email);
        });

        $validator->sometimes('password', 'min:6|confirmed', function ($input) {
            return $input->password;
        });

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());

        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->employee_id = Input::get('employee');

        if(!empty(Input::get('employee')) && Input::get('employee') !== '-1'){
            $user->employee_id = Input::get('employee');
        }

        if ($request->has('password')) {
            $user->password = bcrypt($request->get('password'));
        }

        $user->active = $request->get('active', 0);
        $user->confirmed = $request->get('confirmed', 0);

        $user->save();

        //roles
        if ($request->has('role')) {
            $user->roles()->detach();

            if ($request->get('role')) {
                $user->roles()->attach($request->get('role'));
            }
        }

        return redirect()->intended(route('admin.users'));
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

    //DataTables
    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIndex()
    {
        $users = User::all();
//        return DataTables::of($users)
//            ->addColumn('roles', function($user){
//                if($user->roles != null) {
//                    $name = $user->roles->pluck('name')->implode(',');
//                    return $name;
//                }
//                else{
//                    return "";
//                }
//            })->addColumn('action', function ($user) {
//            return "<a class='btn btn-xs btn-primary' href='users/".$user->id."' data-toggle='tooltip' data-placement='top'><i class='fa fa-eye'></i></a>".
//                "<a class='btn btn-xs btn-info' href='users/".$user->id."/ubah' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";
//        })->make(true);

        return DataTables::of($users)
            ->setTransformer(new UserTransformer)
            ->addIndexColumn()
            ->make(true);
    }
}
