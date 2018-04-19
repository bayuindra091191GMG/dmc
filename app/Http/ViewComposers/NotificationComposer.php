<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 03/04/2018
 * Time: 10:41
 */

namespace App\Http\ViewComposers;


use Illuminate\View\View;

class NotificationComposer
{
    public $notifications;

    public function __construct()
    {
        $this->notifications = auth()->user()->notifications()->limit(5)->get();
    }

    public function compose(View $view)
    {
        $view->with('notifications', $this->notifications);
    }
}