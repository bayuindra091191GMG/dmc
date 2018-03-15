<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 23/01/2018
 * Time: 10:45
 */

namespace App\Transformer\MasterData;


use App\Models\Warehouse;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class WarehouseTransformer extends TransformerAbstract
{
    public function transform(Warehouse $warehouse){
        $action = "<a class='btn btn-xs btn-info' href='warehouses/".$warehouse->id."/ubah' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";
        $action .= "<a class='delete-modal btn btn-xs btn-danger' data-id='". $warehouse->id ."' ><i class='fa fa-trash'></i></a>";

        return[
            'code'          => $warehouse->code,
            'name'          => $warehouse->name,
            'site'          => $warehouse->site->name,
            'action'        => $action
        ];
    }
}