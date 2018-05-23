<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 03/04/2018
 * Time: 10:41
 */

namespace App\Http\ViewComposers;

use App\Models\PermissionMenu;
use App\Models\PermissionMenuHeader;
use Illuminate\View\View;

class NavigationComposer
{
    public $menus;
    public $menuHeader;

    public function __construct()
    {
        $user = auth()->user();
        $role = $user->roles()->pluck('id')[0];
        $this->menus = PermissionMenu::join('menus', 'permission_menus.menu_id', '=', 'menus.id')
            ->where('permission_menus.role_id', $role)
            ->orderBy('menus.index')
            ->get();
        $this->menuHeader = PermissionMenuHeader::join('menu_headers', 'permission_menu_headers.menu_header_id', '=', 'menu_headers.id')
            ->where('permission_menu_headers.role_id', $role)
            ->orderBy('menu_headers.index')
            ->get();
    }

    public function compose(View $view)
    {
        $data = [
            'menus'         => $this->menus,
            'menuHeader'    => $this->menuHeader
        ];
        $view->with($data);
    }
}