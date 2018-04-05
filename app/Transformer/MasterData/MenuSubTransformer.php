<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 23/01/2018
 * Time: 10:45
 */

namespace App\Transformer\MasterData;


use App\Models\Menu;
use App\Models\MenuSub;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class MenuSubTransformer extends TransformerAbstract
{
    public function transform(MenuSub $menuSub){

        $action =
            "<a class='btn btn-xs btn-info' href='menu_subs/".$menuSub->id."/ubah' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";

        return[
            'name'    => $menuSub->name,
            'menu'    => $menuSub->menu->name,
            'route'   => $menuSub->route,
            'action'  => $action
        ];
    }
}