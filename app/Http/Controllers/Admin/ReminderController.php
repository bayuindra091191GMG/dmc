<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 18/07/2018
 * Time: 9:06
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Transformer\ReminderTransformer;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;

class ReminderController extends Controller
{
    public function index(){
        return view('admin.reminders.index');
    }

    public function getIndex()
    {
        $expDate = Carbon::now('Asia/Jakarta')->subDays(5);
        $now = Carbon::now('Asia/Jakarta');
        $headers = Schedule::where('status_id', 3)
            ->where(function ($q) use ($expDate, $now){
                $q->where(function ($q) use ($expDate, $now){
                    $q->whereHas('course', function($query){
                        $query->where('type', 1);
                    })
                        ->whereDate('finish_date', '>=', $expDate);
                })
                ->orWhere(function ($q) use ($expDate, $now){
                    $q->whereHas('course', function($query){
                        $query->where('type', 2);
                    })
                        ->where('meeting_amount', '>', 5)
                        ->whereDate('finish_date', '>=', $expDate);
                });
            })

            ->get();


        return DataTables::of($headers)
            ->setTransformer(new ReminderTransformer)
            ->addIndexColumn()
            ->make(true);
    }
}