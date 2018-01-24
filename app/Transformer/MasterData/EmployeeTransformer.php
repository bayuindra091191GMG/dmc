<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 23/01/2018
 * Time: 10:45
 */

namespace App\Transformer\MasterData;


use App\Models\Employee;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class EmployeeTransformer extends TransformerAbstract
{
    public function transform(Employee $employee){

        $dobDate = Carbon::parse($employee->date_of_birth)->format('d M Y');
        $createdDate = Carbon::parse($employee->created_at)->format('d M Y');

        $updatedDate = '-';
        if(!empty($employee->updated_at)){
            $updatedDate = Carbon::parse($employee->updated_at)->format('d M Y');
        }

        $action = "<a class='btn btn-xs btn-info' href='employees/".$employee->id."/ubah' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";

        return[
            'name'          => $employee->name,
            'email'         => $employee->email,
            'phone'         => $employee->phone,
            'dob'           => $dobDate,
            'address'       => $employee->address,
            'department'    => $employee->department->name,
            'site'          => $employee->site->name,
            'created_at'    => $createdDate,
            'updated_at'    => $updatedDate,
            'action'        => $action
        ];
    }
}