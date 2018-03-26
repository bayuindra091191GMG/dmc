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

        $action = "<a class='btn btn-xs btn-info' href='groups/".$group->id."/ubah' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";
        $action .= "<a class='delete-modal btn btn-xs btn-danger' data-id='". $group->id ."' ><i class='fa fa-trash'></i></a>";

        return[
            'code'          => $group->code ?? '-',
            'name'          => $group->name,
            'type'          => $group->type == 1 ? 'Inventory' : 'BBM atau oli',
            'action'        => $action
        ];
    }
}