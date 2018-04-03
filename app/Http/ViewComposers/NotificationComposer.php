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
    public $testArr;

    public function __construct()
    {
        $this->testArr = auth()->user()->unreadNotifications()->limit(5)->get();
    }

    public function compose(View $view)
    {
//        dd($this->testArr);
        $view->with('testArr', $this->testArr);
    }
}