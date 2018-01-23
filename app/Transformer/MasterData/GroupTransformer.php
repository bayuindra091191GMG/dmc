<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 23/01/2018
 * Time: 10:45
 */

namespace App\Transformer\MasterData;


use App\Models\Group;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class GroupTransformer extends TransformerAbstract
{
    public function transform(Group $group){

        $createdDate = Carbon::parse($group->created_at)->format('d M Y');
        $updatedDate = Carbon::parse($group->updated_at)->format('d M Y');
        $action =
            "<a class='btn btn-xs btn-info' href='groups/".$group->id."/ubah' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";

        return[
            'code'          => $group->code,
            'name'          => $group->name,
            'created_by'    => $group->createdBy->email,
            'created_at'    => $createdDate,
            'updated_by'    => $group->updatedBy->email,
            'updated_at'    => $updatedDate,
            'action'        => $action
        ];
    }
}