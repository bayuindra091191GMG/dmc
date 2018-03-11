<?php
/**
 * Created by PhpStorm.
 * User: hellb
 * Date: 3/11/2018
 * Time: 3:28 PM
 */

namespace App\Transformer\MasterData;


use App\Models\Status;
use League\Fractal\TransformerAbstract;

class StatusTransformer extends TransformerAbstract
{
    public function transform(Status $status){
        $action = "<a class='btn btn-xs btn-info' href='statuses/".$status->id."/ubah' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";
        $action .= "<a class='delete-modal btn btn-xs btn-danger' data-id='". $status->id ."' ><i class='fa fa-trash'></i></a>";

        return[
            'ID'            => $status->id,
            'description'   => $status->description,
            'action'        => $action
        ];
    }
}