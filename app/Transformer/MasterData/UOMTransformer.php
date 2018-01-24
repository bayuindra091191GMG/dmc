<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 23/01/2018
 * Time: 10:45
 */

namespace App\Transformer\MasterData;


use App\Models\Uom;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class UOMTransformer extends TransformerAbstract
{
    public function transform(Uom $uom){

        $action =
            "<a class='btn btn-xs btn-info' href='uoms/".$uom->id."/ubah' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";

        return[
            'description'   => $uom->description,
            'action'        => $action
        ];
    }
}