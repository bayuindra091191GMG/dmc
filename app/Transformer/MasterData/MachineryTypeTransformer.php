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
    public function transform(MachineryType $machineryType){

        $action = "<a class='btn btn-xs btn-info' href='machinery_types/".$machineryType->id."/ubah' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";
        $action .= "<a class='delete-modal btn btn-xs btn-danger' data-id='". $machineryType->id ."' ><i class='fa fa-trash'></i></a>";

        return[
            'name'          => $machineryType->name,
            'code'          => $machineryType->code ?? '-',
            'description'   => $machineryType->description ?? '-',
            'action'        => $action
        ];
    }
}