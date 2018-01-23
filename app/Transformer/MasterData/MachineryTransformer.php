<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 23/01/2018
 * Time: 10:45
 */

namespace App\Transformer\MasterData;


use App\Models\Machinery;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class MachineryTransformer extends TransformerAbstract
{
    public function transform(Machinery $machinery){

        $createdDate = Carbon::parse($machinery->created_at)->format('d M Y');
        $updatedDate = Carbon::parse($machinery->updated_at)->format('d M Y');
        $action =
            "<a class='btn btn-xs btn-info' href='machineries/".$machinery->id."/ubah' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";

        return[
            'code'          => $machinery->code,
            'name'          => $machinery->machinery_type->description,
            'created_by'    => $machinery->createdBy->email,
            'created_at'    => $createdDate,
            'updated_by'    => $machinery->updatedBy->email,
            'updated_at'    => $updatedDate,
            'action'        => $action
        ];
    }
}