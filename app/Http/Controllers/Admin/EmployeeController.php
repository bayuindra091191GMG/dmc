<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 19/01/2018
 * Time: 14:19
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Transformer\MasterData\EmployeeTransformer;
use Yajra\DataTables\DataTables;

class EmployeeController extends Controller
{
    public function index(){
        return View('admin.employees.index');
    }

    public function create(){

    }

    public function store(){

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