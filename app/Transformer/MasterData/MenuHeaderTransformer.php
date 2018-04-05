<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 23/01/2018
 * Time: 10:45
 */

namespace App\Transformer\MasterData;


use App\Models\MenuHeader;
use League\Fractal\TransformerAbstract;

class MenuHeaderTransformer extends TransformerAbstract
{
    public function transform(MenuHeader $menuHeader){

        $action =
            "<a class='btn btn-xs btn-info' href='menu_headers/".$menuHeader->id."/ubah' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";

        return[
            'name'           => $menuHeader->name,
            'action'         => $action
        ];
    }
}