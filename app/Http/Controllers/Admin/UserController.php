<?php

namespace App\Http\Controllers\Admin;

use App\Models\Auth\Role\Role;
use App\Models\Auth\User\User;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Site;
use App\Transformer\MasterData\UserTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
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
        $data = [
            'filterStatus'      => $request->input('status') ?? 1
        ];


        return view('admin.users.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $departments = Department::all();
        $sites = Site::all();
        $roles = Role::all();

        $data = [
          'departments'     => $departments,
          'sites'           => $sites,
          'roles'           => $roles
        ];

        return View('admin.users.create')->with($data);
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
            'name'      => 'required|max:100',
            'email'     => 'required|regex:/^\S*$/u|unique:users|max:50',
            'address'   => 'max:200'
        ],[
            'email.unique'      => 'ID Login Akses telah terdaftar!',
            'email.regex'       => 'ID Login Akses harus tanpa spasi!'
        ]);

        $validator->sometimes('password', 'min:6|confirmed', function ($input) {
            return $input->password;
        });

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors())->withInput($request->all());

        if($request->input('department') === '-1'){
            return redirect()->back()->withErrors('Pilih departemen!', 'default')->withInput($request->all());
        }

        if($request->input('site') === '-1'){
            return redirect()->back()->withErrors('Pilih site!', 'default')->withInput($request->all());
        }

        $user = Auth::user();
        $now = Carbon::now('Asia/Jakarta');

        // Create new employee
        $employee = Employee::create([
            'name'          => $request->input('name'),
            'email'         => $request->input('email'),
            'address'       => $request->input('address'),
            'department_id' => $request->input('department'),
            'site_id'       => $request->input('site'),
            'status_id'     => 1,
            'created_by'    => $user->id,
            'created_at'    => $now
        ]);

        // Create new user
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->employee_id = $employee->id;

        if ($request->has('password')) {
            $user->password = bcrypt($request->input('password'));
        }

        $user->created_by = $user->id;
        $user->updated_by = $user->id;
        $user->save();

        $user->roles()->attach($request->input('role'));

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
        $employee = $user->employee;
        $departments = Department::all();
        $sites = Site::all();
        $roles = Role::all();
        $dob = Carbon::parse($employee->date_of_birth)->format('d M Y');

        $data = [
            'user'          => $user,
            'employee'      => $employee,
            'departments'   => $departments,
            'sites'         => $sites,
            'dob'           => $dob,
            'roles'         => $roles
        ];

        return view('admin.users.edit')->with($data);
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
        $employeeId = $request->input('employee_id');
        $validator = Validator::make($request->all(), [
            'name'      => 'required|max:100',
            'email' => [
                'required',
                'max:50',
                'regex:/^\S*$/u',
                Rule::unique('users')->ignore($user->id)
            ],
            'address'   => 'max:200'
        ],[
            'email.unique'      => 'ID Login Akses telah terdaftar!',
            'email.regex'       => 'ID Login Akses harus tanpa spasi!',
        ]);

        $validator->sometimes('password', 'min:6|confirmed', function ($input) {
            return $input->password;
        });

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());

        // Update employee
        $userAuth = Auth::user();
        $now = Carbon::now('Asia/Jakarta');

        $employee = Employee::find($employeeId);
        $employee->name = $request->input('name');
        $employee->email = $request->input('email');
        $employee->address = $request->input('address');
        $employee->department_id = $request->input('department');
        $employee->site_id = $request->input('site');
        $employee->status_id = $request->input('status');
        $employee->updated_by = $userAuth->id;
        $employee->updated_at = $now;

        $employee->save();

        // Update user
        $user->name = $request->input('name');
        if($user->email != $request->input('email')) $user->email = $request->input('email');

        if ($request->has('password')) {
            $user->password = bcrypt($request->input('password'));
        }

        $user->status_id = $request->input('status');
        $user->created_by = $userAuth->id;
        $user->updated_by = $userAuth->id;
        $user->save();

        //roles
        if ($request->has('role')) {
            $user->roles()->detach();
            $user->roles()->attach($request->input('role'));

        }

        Session::flash('message', 'Berhasil mengubah data user!');

        return redirect()->intended(route('admin.users',['user' => $user]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        try{
            $userAuth = Auth::user();
            $now = Carbon::now('Asia/Jakarta');

            $user = User::find($request->input('id'));
            $user->status_id = 10;
            $user->updated_by = $userAuth->id;
            $user->updated_at = $now->toDateTimeString();
            $user->save();

            Session::flash('message', 'Berhasil menghapus data user '. $user->email);
            return Response::json(array('success' => 'VALID'));
        }
        catch(\Exception $ex){
            return Response::json(array('errors' => 'INVALID'));
        }
    }

    //DataTables

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function getIndex(Request $request)
    {
        try{

            if($request->filled('status')){
                $users = User::where('status_id', $request->input('status'))
                    ->orderBy('created_at','DESC')
                    ->get();
            }
            else{
                $users = User::where('status_id', 1)
                    ->orderBy('created_at','DESC')
                    ->get();
            }

            return DataTables::of($users)
                ->setTransformer(new UserTransformer)
                ->addIndexColumn()
                ->make(true);
        }
        catch (\Exception $ex){
            error_log($ex);
        }
    }
}
