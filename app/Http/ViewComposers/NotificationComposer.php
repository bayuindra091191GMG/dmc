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
    public $data;

    public function __construct()
    {
        $this->notifications = auth()->user()->notifications()->limit(5)->get();
        $isRead = true;
        foreach($this->notifications as $notification){
            if($notification->unread()){
                $isRead = false;
            }
        }

        $this->data = [
            'notifications' => $this->notifications,
            'isRead'        => $isRead
        ];
    }

    public function compose(View $view)
    {
        $view->with($this->data);
    }
}