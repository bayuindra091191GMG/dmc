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

        $action = "<a class='btn btn-xs btn-info' href='departments/".$department->id."/ubah' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";
        $action .= "<a class='delete-modal btn btn-xs btn-danger' data-id='". $department->id ."' ><i class='fa fa-trash'></i></a>";

        return[
            'code'          => $department->code,
            'name'          => $department->name,
            'created_by'    => $department->createdBy->email,
            'created_at'    => $createdDate,
            'action'        => $action
        ];
    }
}