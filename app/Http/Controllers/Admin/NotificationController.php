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
use App\Transformer\NotificationTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class NotificationController extends Controller
{
    public function index(){
        return view('admin.notifications.index');
    }

    public function read(Request $request){
        $user = \Auth::user();
        foreach ($user->unreadNotifications as $notification) {
            $notification->markAsRead();
        }
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getIndex(){
        $notifications = auth()->user()->notifications()->get();
        return DataTables::of($notifications)
            ->setTransformer(new NotificationTransformer)
            ->addIndexColumn()
            ->make(true);
    }
}