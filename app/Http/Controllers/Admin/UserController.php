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
        return view('admin.users.index');
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
            'code'      => 'required|max:30|regex:/^\S*$/u|unique:employees',
            'name'      => 'required|max:100',
            'email'     => 'required|email|unique:users|max:100',
//            'phone'     => 'max:20',
            'address'   => 'max:200'
        ],[
            'code.unique'       => 'Kode karyawan telah terpakai!',
            'code.regex'        => 'Kode karyawan harus tanpa spasi!',
            'email.unique'      => 'Email telah terdaftar!',
        ]);

        $validator->sometimes('password', 'min:6|confirmed', function ($input) {
            return $input->password;
        });

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors()->withInput($request->all()));

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
            'code'          => $request->input('code'),
            'email'         => $request->input('email'),
//            'phone'         => $request->input('phone'),
            'address'       => $request->input('address'),
            'department_id' => $request->input('department'),
            'site_id'       => $request->input('site'),
            'status_id'     => 1,
            'created_by'    => $user->id,
            'created_at'    => $now
        ]);

        if($request->filled('dob')){
            $dob = Carbon::createFromFormat('d M Y', Input::get('dob'), 'Asia/Jakarta');
            $employee->date_of_birth = $dob->toDateString();
            $employee->save();
        }

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
        dd($user->id);

        $employeeId = $request->input('employee_id');
        $validator = Validator::make($request->all(), [
            'name'      => 'required|max:100',
            'email' => [
                'required',
                'max:100',
                'email',
                Rule::unique('users')->ignore($user->id)
            ],
            'phone'     => 'max:20',
            'address'   => 'max:200'
        ],[
            'email.unique'      => 'Email telah terdaftar!',
        ]);

        $validator->sometimes('password', 'min:6|confirmed', function ($input) {
            return $input->password;
        });

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());

        // Update employee
        $user = Auth::user();
        $now = Carbon::now('Asia/Jakarta');

        $employee = Employee::find($employeeId);
        $employee->name = $request->input('name');
        $employee->email = $request->input('email');
        $employee->phone = $request->input('phone');
        $employee->address = $request->input('address');
        $employee->department_id = $request->input('department');
        $employee->site_id = $request->input('site');
        $employee->status_id = $request->input('status');
        $employee->updated_by = $user->id;
        $employee->updated_at = $now;

        if($request->filled('dob')){
            $dob = Carbon::createFromFormat('d M Y', Input::get('dob'), 'Asia/Jakarta');
            $employee->date_of_birth = $dob->toDateString();
        }

        $employee->save();

        // Update user
        $user->name = $request->input('name');
        if($user->email != $request->input('email')) $user->email = $request->input('email');

        if ($request->has('password')) {
            $user->password = bcrypt($request->input('password'));
        }

        $user->created_by = $user->id;
        $user->updated_by = $user->id;
        $user->save();

        //roles
        if ($request->has('role')) {
            $user->roles()->detach();

            if ($request->input('role')) {
                $user->roles()->attach($request->get('role'));
            }
        }

        Session::flash('message', 'Berhasil mengubah data user!');

        return redirect()->intended(route('admin.users.edit',['user' => $user]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return void
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
     * @throws \Exception
     */
    public function getIndex()
    {
        try{
            $users = User::where('status_id', 1)->get();
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
