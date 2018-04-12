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
use App\Models\UsersRole;
use Illuminate\View\View;

class NavigationComposer
{
    public $menus;
    public $menuHeader;

    public function __construct()
    {
//        $this->testArr = auth()->user()->unreadNotifications()->limit(5)->get();
        //Try to get the menu permissions and all the Menu
        $user = auth()->user();
        $role = $user->roles()->pluck('id')[0];
        $this->menus = PermissionMenu::where('role_id', $role)->get();
        $this->menuHeader = PermissionMenuHeader::where('role_id', $role)->get();
        //dd($this->menuHeader);
    }

    public function compose(View $view)
    {
//        dd($this->testArr);
        $data = [
            'menus'         => $this->menus,
            'menuHeader'    => $this->menuHeader
        ];
        $view->with($data);
    }
}