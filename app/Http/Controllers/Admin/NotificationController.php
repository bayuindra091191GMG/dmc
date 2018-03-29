<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 28/03/2018
 * Time: 13:30
 */

namespace App\Http\Controllers\Admin;


use App\Events\TestEvent;
use App\Http\Controllers\Controller;
use App\Notifications\TestingNotify;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;

class NotificationController extends Controller
{
    public function testNotify(){
//        $user = Auth::user();
//        $user->notify(new TestingNotify($user));
        Event::fire(new TestEvent('TEST'));
    }

    public function notifications(){
        error_log('test');
    }
}