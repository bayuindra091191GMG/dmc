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
        $schedules = Schedule::where('status_id', 3)->get();
        $now = Carbon::now('Asia/Jakarta');

        $reminders = new Collection();
        foreach ($schedules as $schedule){
            $remindDate = Carbon::parse($schedule->finish_date)->subDays(5);
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
        }

        return DataTables::of($reminders)
            ->setTransformer(new ReminderTransformer)
            ->addIndexColumn()
            ->make(true);
    }

    /**
     * Renew customer schedule based on taken course
     *
     * @param  \Illuminate\Http\Request $request
     * @return JsonResponse
     */
    public function renew(Request $request){
        try{
            $scheduleId = $request->input('schedule_id');

            $schedule = Schedule::find($scheduleId);

            if($schedule->status_id !== 3){
                return Response::json(array('errors' => 'INVALID'));
            }

            // If course type is package
            if($schedule->course->type === 1){
                $finishDate = Carbon::parse($schedule->finish_date)->addDays($schedule->course->valid);
                $schedule->finish_date = $finishDate->toDateTimeString();

                $remindDate = Carbon::parse($schedule->finish_date)->subDays(5);
                $realFinishDate = Carbon::parse($schedule->finish_date);
                $now = Carbon::now('Asia/Jakarta');

                if($now->greaterThanOrEqualTo($remindDate) && $now->lessThanOrEqualTo($realFinishDate)){
                    $schedule->meeting_amount += $schedule->course->meeting_amount;
                }
                else{
                    $schedule->meeting_amount = $schedule->course->meeting_amount;
                }
            }
            else{
                $finishDate = Carbon::parse($schedule->finish_date)->addMonths(1);
                $schedule->finish_date = $finishDate->toDateTimeString();
                $schedule->meeting_amount = 0;
            }

            $schedule->status_id = 2;
            $schedule->save();

            Session::flash('message', 'Berhasil memperbarui jadwal customer!');

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
     * @param  \Illuminate\Http\Request $request
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

            Session::flash('message', 'Berhasil menghapus jadwal customer!');

            return new JsonResponse($schedule);
        }
        catch (\Exception $ex){
            error_log($ex);
            return Response::json(array('errors' => 'INVALID'));
        }
    }
}