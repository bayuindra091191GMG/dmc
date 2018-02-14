<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 19/01/2018
 * Time: 14:19
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Site;
use App\Transformer\MasterData\EmployeeTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class EmployeeController extends Controller
{
    public function index(){
        return View('admin.employees.index');
    }

    public function create(){
        $departments = Department::all();
        $sites = Site::all();

        $data = [
            'departments'   => $departments,
            'sites'         => $sites
        ];

        return View('admin.employees.create')->with($data);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name'      => 'required|max:100',
            'code'      => 'max:45',
            'email'     => 'email|max:45',
            'phone'     => 'max:45',
            'address'   => 'max:300'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        if(Input::get('department') === '-1'){
            return redirect()->back()->withErrors('Pilih departemen!', 'default')->withInput($request->all());
        }

        if(Input::get('site') === '-1'){
            return redirect()->back()->withErrors('Pilih site!', 'default')->withInput($request->all());
        }

        $user = Auth::user();

        $now = Carbon::now('Asia/Jakarta');

        $employee = Employee::create([
            'name'          => Input::get('name'),
            'code'          => Input::get('code'),
            'email'         => Input::get('email'),
            'phone'         => Input::get('phone'),
            'address'       => Input::get('address'),
            'department_id' => Input::get('department'),
            'site_id'       => Input::get('site'),
            'status_id'     => 1,
            'created_by'    => $user->id,
            'created_at'    => $now
        ]);

        if(!empty(Input::get('dob'))){
            $dob = Carbon::createFromFormat('d M Y', Input::get('dob'), 'Asia/Jakarta');
            $employee->date_of_birth = $dob->toDateString();
            $employee->save();
        }

        Session::flash('message', 'Berhasil membuat data karyawan baru!');

        return redirect()->route('admin.employees');
    }

    public function edit( Employee $employee){
        $departments = Department::all();
        $sites = Site::all();
        $dob = Carbon::parse($employee->date_of_birth)->format('d M Y');

        $data = [
            'departments'   => $departments,
            'sites'         => $sites,
            'employee'      => $employee,
            'dob'           => $dob
        ];

        return View('admin.employees.edit')->with($data);
    }

    public function update(Request $request, Employee $employee){
        $validator = Validator::make($request->all(),[
            'name'      => 'required|max:100',
            'email'     => 'email|max:45',
            'phone'     => 'max:45',
            'address'   => 'max:300'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        if(Input::get('department') === '-1'){
            return redirect()->back()->withErrors('Pilih departemen!', 'default')->withInput($request->all());
        }

        if(Input::get('site') === '-1'){
            return redirect()->back()->withErrors('Pilih site!', 'default')->withInput($request->all());
        }

        $user = Auth::user();
        $now = Carbon::now('Asia/Jakarta');

        $employee->name = Input::get('name');
        $employee->code = Input::get('code');
        $employee->email = Input::get('email');
        $employee->phone = Input::get('phone');
        $employee->address = Input::get('address');
        $employee->department_id = Input::get('department');
        $employee->site_id = Input::get('site');
        $employee->status_id = Input::get('statuses');
        $employee->updated_by = $user->id;
        $employee->updated_at = $now;

        if(!empty(Input::get('dob'))){
            $dob = Carbon::createFromFormat('d M Y', Input::get('dob'), 'Asia/Jakarta');
            $employee->date_of_birth = $dob->toDateString();
        }

        $employee->save();

        Session::flash('message', 'Berhasil mengubah data karyawan!');

        return redirect()->route('admin.employees.edit', [ 'employee' => $employee->id]);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getIndex(){
        $employees = Employee::all();
        return DataTables::of($employees)
            ->setTransformer(new EmployeeTransformer)
            ->make(true);
    }

    public function getEmployees(Request $request){
        $term = trim($request->q);
        $employees= Employee::where('name', 'LIKE', '%'. $term. '%')
            ->orWhere('code', 'LIKE', '%'. $term. '%')
            ->get();

        $formatted_tags = [];

        foreach ($employees as $employee) {
            $formatted_tags[] = ['id' => $employee->id, 'text' => $employee->code. ' '. $employee->name];
        }

        return \Response::json($formatted_tags);
    }
}