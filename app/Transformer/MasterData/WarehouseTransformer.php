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

        $createdDate = Carbon::parse($warehouse->created_at)->format('d M Y');
        $updatedDate = '-';
        if(!empty($warehouse->updated_at)){
            $updatedDate = Carbon::parse($warehouse->updated_at)->format('d M Y');
        }
        $updatedBy = '-';
        if(!empty($warehouse->updated_by)){
            $warehouse->updatedBy->email;
        }
        $action = "<a class='btn btn-xs btn-info' href='warehouses/".$warehouse->id."/ubah' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";
        $action .= "<a class='delete-modal btn btn-xs btn-danger' data-id='". $warehouse->id ."' ><i class='fa fa-trash'></i></a>";

        return[
            'code'          => $warehouse->code,
            'name'          => $warehouse->name,
            'location'      => $warehouse->location,
            'phone'         => $warehouse->phone,
            'created_by'    => $warehouse->createdBy->email,
            'created_at'    => $createdDate,
            'updated_by'    => $updatedBy,
            'updated_at'    => $updatedDate,
            'action'        => $action
        ];
    }
}