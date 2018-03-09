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

        $purchaseDate = "-";
        if(!empty($machinery->purchase_date)){
            $purchaseDate = Carbon::parse($machinery->purchase_date)->format('d M Y');
        }

        $action = "<a class='btn btn-xs btn-info' href='machineries/".$machinery->id."/ubah' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";
        $action .= "<a class='delete-modal btn btn-xs btn-danger' data-id='". $machinery->id ."' ><i class='fa fa-trash'></i></a>";

        return[
            'code'          => $machinery->code,
            'type'          => $machinery->machinery_type->name,
            'brand'         => $machinery->machinery_brand->name,
            'category'      => $machinery->machinery_category->name,
            'purchase_date' => $purchaseDate,
            'status'        => $machinery->status->description,
            'created_at'    => $createdDate,
            'action'        => $action
        ];
    }
}