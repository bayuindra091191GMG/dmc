<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 23/01/2018
 * Time: 10:45
 */

namespace App\Transformer\MasterData;

use App\Models\PermissionMenu;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class PermissionMenuTransformer extends TransformerAbstract
{
    public function transform(PermissionMenu $permissionMenu){

        $createdDate = Carbon::parse($permissionMenu->created_at)->format('d M Y');
        $updatedDate = Carbon::parse($permissionMenu->updated_at)->format('d M Y');
        $action =
            "<a class='btn btn-xs btn-info' href='permission_menus/hapus/".$permissionMenu->id."' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";

        return[
            'role'          => $permissionMenu->role->name,
            'menu'          => $permissionMenu->menu->name,
            'created_by'    => $permissionMenu->createdBy->email,
            'created_at'    => $createdDate,
            'updated_by'    => $permissionMenu->updatedBy->email,
            'updated_at'    => $updatedDate,
            'action'        => $action
        ];
    }
}