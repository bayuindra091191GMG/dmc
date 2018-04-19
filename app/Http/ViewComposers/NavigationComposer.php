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
        $this->menus = PermissionMenu::where('role_id', $role)
            ->orderBy('menu_id')
            ->get();
        $this->menuHeader = PermissionMenuHeader::where('role_id', $role)->orderby('menu_header_id')
            ->orderBy('menu_header_id')
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