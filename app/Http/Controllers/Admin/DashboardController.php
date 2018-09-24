<?php

namespace App\Http\Controllers\Admin;

use App\Models\ApprovalPurchaseOrder;
use App\Models\ApprovalPurchaseRequest;
use App\Models\ApprovalRule;
use App\Models\Auth\Role\Role;
use App\Models\Auth\User\User;
use App\Models\Course;
use App\Models\Customer;
use App\Models\ItemStockNotification;
use App\Models\PreferenceCompany;
use App\Models\PurchaseOrderHeader;
use App\Models\PurchaseRequestHeader;
use App\Models\Schedule;
use Arcanedev\LogViewer\Entities\Log;
use Arcanedev\LogViewer\Entities\LogEntry;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        //get nearly finish schedule
//        $temp = Carbon::now('Asia/Jakarta');
//        $start = Carbon::parse(date_format($temp,'d M Y'));
//
//        $temp2 = Carbon::now('Asia/Jakarta')->addDays(14);
//        $end = Carbon::parse(date_format($temp2,'d M Y'));
//        $scheduleFinishCount = Schedule::whereBetween('finish_date', array($start->toDateTimeString(), $end->toDateTimeString()))->take(5)->get();

        $counts = [
            'users' => \DB::table('users')->count(),
            'users_unconfirmed' => \DB::table('users')->where('confirmed', false)->count(),
            'users_inactive' => \DB::table('users')->where('active', false)->count(),
            'protected_pages' => 0,
        ];

        $totalCustomer = Customer::all();
        $totalCourse = Course::all();

        $schedules = Schedule::where('status_id', 3)->get();
        $now = Carbon::now('Asia/Jakarta');

        $packageReminders = new Collection();
        $classReminders = new Collection();
        $privateReminders = new Collection();
        foreach ($schedules as $schedule){
            $remindDate = Carbon::parse($schedule->finish_date)->subDays(5);
            if($schedule->course->type === 1){
                if($packageReminders->count() < 5){
                    if($now->greaterThanOrEqualTo($remindDate)){
                        if($schedule->meeting_amount > 5){
                            $packageReminders->add($schedule);
                        }
                    }
                }
            }
            elseif($schedule->course->type === 2){
                if($classReminders->count() < 5){
                    if($now->greaterThanOrEqualTo($remindDate)){
                        $classReminders->add($schedule);
                    }
                }
            }
            elseif($schedule->course->type === 3){
                if($privateReminders->count() < 5){
                    if($schedule->meeting_amount === 0){
                        $privateReminders->add($schedule);
                    }
                }
            }

            if($packageReminders->count() == 5 &&
                $classReminders->count() == 5 &&
                $privateReminders->count() == 5){
                break 1;
            }
        }

        $data = [
            'counts'                    => $counts,
//            'scheduleFinishCount'       => $scheduleFinishCount,
            'totalCustomer'             => $totalCustomer->count(),
            'totalClass'                => $totalCourse->count(),
            'packageReminders'          => $packageReminders,
            'classReminders'            => $classReminders,
            'privateReminders'          => $privateReminders
        ];

        return view('admin.dashboard')->with($data);
    }

    public function getAllWarning(){
        //get nearly finish schedule
        $temp = Carbon::now('Asia/Jakarta');
        $start = Carbon::parse(date_format($temp,'d M Y'));

        $temp2 = Carbon::now('Asia/Jakarta')->addDays(14);
        $end = Carbon::parse(date_format($temp2,'d M Y'));
        $allWarning = Schedule::whereBetween('finish_date', array($start->toDateTimeString(), $end->toDateTimeString()))->get();

        $data = [
            'allWarning'               => $allWarning
        ];

        return view('admin.warning')->with($data);
    }

    public function getLogChartData(Request $request)
    {
        \Validator::make($request->all(), [
            'start' => 'required|date|before_or_equal:now',
            'end' => 'required|date|after_or_equal:start',
        ])->validate();

        $start = new Carbon($request->get('start'));
        $end = new Carbon($request->get('end'));

        $dates = collect(\LogViewer::dates())->filter(function ($value, $key) use ($start, $end) {
            $value = new Carbon($value);
            return $value->timestamp >= $start->timestamp && $value->timestamp <= $end->timestamp;
        });


        $levels = \LogViewer::levels();

        $data = [];

        while ($start->diffInDays($end, false) >= 0) {

            foreach ($levels as $level) {
                $data[$level][$start->format('Y-m-d')] = 0;
            }

            if ($dates->contains($start->format('Y-m-d'))) {
                /** @var  $log Log */
                $logs = \LogViewer::get($start->format('Y-m-d'));

                /** @var  $log LogEntry */
                foreach ($logs->entries() as $log) {
                    $data[$log->level][$log->datetime->format($start->format('Y-m-d'))] += 1;
                }
            }

            $start->addDay();
        }

        return response($data);
    }

    public function getRegistrationChartData()
    {

        $data = [
            'registration_form' => User::whereDoesntHave('providers')->count(),
            'google' => User::whereHas('providers', function ($query) {
                $query->where('provider', 'google');
            })->count(),
            'facebook' => User::whereHas('providers', function ($query) {
                $query->where('provider', 'facebook');
            })->count(),
            'twitter' => User::whereHas('providers', function ($query) {
                $query->where('provider', 'twitter');
            })->count(),
        ];

        return response($data);
    }
}
