<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 01/02/2018
 * Time: 15:04
 */

namespace App\Transformer\MasterData;

use App\Models\MachineryCategory;
use League\Fractal\TransformerAbstract;

class MachineryCategoryTransformer extends TransformerAbstract
{
    public function transform(MachineryCategory $machineryCategory){
        $action = "<a class='btn btn-xs btn-info' href='machinery_categories/".$machineryCategory->id."/ubah' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";
        $action .= "<a class='delete-modal btn btn-xs btn-danger' data-id='". $machineryCategory->id ."' ><i class='fa fa-trash'></i></a>";

        return[
            'name'          => $machineryCategory->name,
            'code'          => $machineryCategory->code ?? '-',
            'description'   => $machineryCategory->description ?? '-',
            'action'        => $action
        ];
    }
}