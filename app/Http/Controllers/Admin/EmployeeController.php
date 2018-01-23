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
            'email'     => 'email|max:45',
            'phone'     => 'numeric|max:45',
            'dob'       => 'required',
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
        $dob = Carbon::createFromFormat('d M y', Input::get('dob'), 'Asia/Jakarta');

        $employee = Employee::create([
            'name'          => Input::get('name'),
            'email'         => Input::get('email'),
            'phone'         => Input::get('phone'),
            'dob'           => $dob->toDateString(),
            'address'       => Input::get('address'),
            'created_by'    => $user->id,
            'created_at'    => $now
        ]);

        Session::flash('message', 'Berhasil membuat data karyawan baru!');

        return redirect()->route('admin.employees.create');
    }

    public function edit(){

    }

    public function update(){

    }

    public function getIndex(){
        $employees = Employee::all();
        return DataTables::of($employees)
            ->setTransformer(new EmployeeTransformer)
            ->make(true);
    }
}