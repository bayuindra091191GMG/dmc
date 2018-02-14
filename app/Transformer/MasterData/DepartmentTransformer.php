<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 23/01/2018
 * Time: 10:45
 */

namespace App\Transformer\MasterData;


use App\Models\Department;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class DepartmentTransformer extends TransformerAbstract
{
    public function transform(Department $department){

        $createdDate = Carbon::parse($department->created_at)->format('d M Y');

        $updatedDate = '-';
        if(!empty($department->updated_at)){
            $updatedDate = Carbon::parse($department->updated_at)->format('d M Y');
        }
        $updatedBy = '-';
        if(!empty($department->updated_by)){
            $department->updatedBy->email;
        }

        $action =
            "<a class='btn btn-xs btn-info' href='departments/".$department->id."/ubah' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";

        return[
            'code'          => $department->code,
            'name'          => $department->name,
            'created_by'    => $department->createdBy->email,
            'created_at'    => $createdDate,
            'updated_by'    => $updatedBy,
            'updated_at'    => $updatedDate,
            'action'        => $action
        ];
    }
}