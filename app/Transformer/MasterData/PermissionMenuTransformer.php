<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 23/01/2018
 * Time: 10:45
 */

namespace App\Transformer\MasterData;

use App\Models\Auth\Role\Role;
use App\Models\PermissionMenu;
use League\Fractal\TransformerAbstract;

class PermissionMenuTransformer extends TransformerAbstract
{
    public function transform(Role $role){
        $permissionMenuRoute = route('admin.permission_menus.show', ['permission_menu' => $role->id]);
        $roleName =  "<a style='text-decoration: underline;' href='" . $permissionMenuRoute. "' target='_blank'>". $role->description . "</a>";
        $action =
            "<a class='btn btn-xs btn-info' href='permission_menus/". $role->id."/ubah' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";

        $permission = PermissionMenu::where('role_id', $role->id)->count();

        return[
            'role'          => $roleName,
            'permission'    => $permission,
            'action'        => $action
        ];
    }
}