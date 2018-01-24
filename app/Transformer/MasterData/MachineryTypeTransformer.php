<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 23/01/2018
 * Time: 10:45
 */

namespace App\Transformer\MasterData;


use App\Models\MachineryType;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class MachineryTypeTransformer extends TransformerAbstract
{
    public function transform(MachineryType $machinery_type){

        $createdDate = Carbon::parse($machinery_type->created_at)->format('d M Y');
        $updatedDate = Carbon::parse($machinery_type->updated_at)->format('d M Y');
        $action =
            "<a class='btn btn-xs btn-info' href='machinery_types/".$machinery_type->id."/ubah' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";

        return[
            'description'   => $machinery_type->description,
            'action'        => $action
        ];
    }
}