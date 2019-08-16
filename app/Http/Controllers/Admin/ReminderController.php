<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 18/07/2018
 * Time: 9:06
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\CourseDetail;
use App\Models\Schedule;
use App\Transformer\ReminderTransformer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class ReminderController extends Controller
{
    public function index(){
        return view('admin.reminders.index');
    }

    /**
     * Get list of reminder
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function getIndex()
    {
        $schedules = Schedule::all();
        $now = Carbon::now('Asia/Jakarta');

        $reminders = new Collection();
        foreach ($schedules as $schedule){
            $remindDate = Carbon::parse($schedule->finish_date)->subDays(15);
            if($schedule->course->type === 1){
                if($now->greaterThanOrEqualTo($remindDate)){
                    if($schedule->meeting_amount > 5){
                        $reminders->add($schedule);
                    }
                }
            }
            elseif($schedule->course->type === 2){
                if($now->greaterThanOrEqualTo($remindDate)){
                    $reminders->add($schedule);
                }
            }
            elseif($schedule->course->type === 3){
                if($schedule->meeting_amount === 0){
                    $reminders->add($schedule);
                }
            }
            elseif($schedule->course->type === 4){
                if($now->greaterThanOrEqualTo($remindDate)){
                    $reminders->add($schedule);
                }
            }
        }

        return DataTables::of($reminders)
            ->setTransformer(new ReminderTransformer)
            ->addIndexColumn()
            ->make(true);
    }

    /**
     * Renew customer schedule based on taken course
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function renew(Request $request){
        try{
            $scheduleId = $request->input('schedule_id');

            $schedule = Schedule::find($scheduleId);

            if($schedule->status_id !== 3){
                return Response::json(array('errors' => 'INVALID'));
            }
            // If course type is Muaythai
            if($schedule->course->type === 1){
                //$finishDate = Carbon::parse($schedule->finish_date)->addDays($schedule->course->valid);
                $finishDate  = Carbon::now('Asia/Jakarta')->addDays($schedule->course->valid);
                $schedule->finish_date = $finishDate->toDateTimeString();

//                $remindDate = Carbon::parse($schedule->finish_date)->subDays(5);
                $realFinishDate = Carbon::parse($schedule->finish_date);
                $carryOverFinishDate = $realFinishDate->subDays(7);
                $now = Carbon::now('Asia/Jakarta');

                if($now->lessThanOrEqualTo($carryOverFinishDate)){
                    // Carry over previous Muaythai meeting amount
                    $schedule->meeting_amount += $schedule->course->meeting_amount;
                }
                else{
                    if($now->lessThanOrEqualTo($realFinishDate)){
                        $schedule->meeting_amount = $schedule->course->meeting_amount;
                    }
                }
            }
            // If course type is Dance
            elseif($schedule->course->type === 2){
                //$finishDate = Carbon::parse($schedule->finish_date);

                // Get next month date at 10th
                $nextMonthDate = Carbon::now('Asia/Jakarta')->addMonthsNoOverflow(1);
                $month = $nextMonthDate->month;
                $year = $nextMonthDate->year;
                $nextMonthFinishDate = Carbon::create($year, $month, 10, 0, 0, 0);

                $schedule->finish_date = $nextMonthFinishDate->toDateTimeString();
                $schedule->meeting_amount = 0;
            }
            // If course type is Gymnastic
            elseif($schedule->course->type === 4){
                //$finishDate = Carbon::parse($schedule->finish_date);

                // Get next month date at 10th
                $nextMonthDate = Carbon::now('Asia/Jakarta')->addMonthsNoOverflow(1);
                $month = $nextMonthDate->month;
                $year = $nextMonthDate->year;
                $nextMonthFinishDate = Carbon::create($year, $month, 10, 0, 0, 0);

                $schedule->finish_date = $nextMonthFinishDate->toDateTimeString();
                $schedule->meeting_amount = 0;
            }

            $schedule->status_id = 2;
            $schedule->save();

            // Decrease student count
            $splitted = explode('-', $schedule->day);
            $dayString = trim($splitted[0]);

            if(!empty($splitted[1])){
                $timeString = trim($splitted[1]);

                $courseDetail = CourseDetail::where('course_id', $schedule->course_id)
                    ->where('day_name', $dayString)
                    ->where('time', $timeString)
                    ->first();
                $courseDetail->current_capacity -= 1;
                $courseDetail->save();
            }

            if($schedule->course->type === 3){
                return redirect()->route('admin.transactions.private.create')->with('customer', $schedule->customer_id);
            }

            Session::flash('message', 'Berhasil memperbaharui jadwal Student!');

            return new JsonResponse($schedule);
        }
        catch (\Exception $ex){
            error_log($ex);
            return Response::json(array('errors' => 'INVALID'));
        }
    }

    /**
     * Disable customer schedule based on taken course
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function disable(Request $request){
        try{
            $scheduleId = $request->input('schedule_id');

            $schedule = Schedule::find($scheduleId);

            if($schedule->status_id !== 3){
                return Response::json(array('errors' => 'INVALID'));
            }

            $schedule->status_id = 5;
            $schedule->save();

            // Decrease student count
            $splitted = explode('-', $schedule->day);
            $dayString = trim($splitted[0]);
            $timeString = trim($splitted[1]);

            $courseDetail = CourseDetail::where('course_id', $schedule->course_id)
                ->where('day_name', $dayString)
                ->where('time', $timeString)
                ->first();
            $courseDetail->current_capacity -= 1;
            $courseDetail->save();

            Session::flash('message', 'Berhasil menghapus jadwal customer!');

            return new JsonResponse($schedule);
        }
        catch (\Exception $ex){
            error_log($ex);
            return Response::json(array('errors' => 'INVALID'));
        }
    }
}