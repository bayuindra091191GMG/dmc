<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 01/02/2018
 * Time: 15:45
 */

namespace App\Transformer\MasterData;


use App\Models\MachineryBrand;
use League\Fractal\TransformerAbstract;

class MachineryBrandTransformer extends TransformerAbstract
{
    public function transform(MachineryBrand $machineryBrand){
        $action = "<a class='btn btn-xs btn-info' href='machinery_brands/".$machineryBrand->id."/ubah' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";
        $action .= "<a class='delete-modal btn btn-xs btn-danger' data-id='". $machineryBrand->id ."' ><i class='fa fa-trash'></i></a>";

        return[
            'name'          => $machineryBrand->name,
            'code'          => $machineryBrand->code ?? '-',
            'description'   => $machineryBrand->description ?? '-',
            'action'        => $action
        ];
    }
}