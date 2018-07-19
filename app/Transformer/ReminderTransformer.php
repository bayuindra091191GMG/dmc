<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 18/07/2018
 * Time: 9:34
 */

namespace App\Transformer;


use App\Models\Schedule;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class ReminderTransformer extends TransformerAbstract
{
    public function transform(Schedule $schedule){

        $finishDate = Carbon::parse($schedule->finish_date)->format('d M Y');

        $action = "<button class='btn btn-success modal-renew' data-schedule-id='". $schedule->id. "' data-course='". $schedule->course->name. "' data-customer='". $schedule->customer->name. "' data-customer-parent='". $schedule->customer->parent_name. "'>PERBARUI</button>";
        $action .= "<button class='btn btn-danger modal-disable' data-schedule-id='". $schedule->id. "' data-course='". $schedule->course->name. "' data-customer='". $schedule->customer->name. "' data-customer-parent='". $schedule->customer->parent_name. "'>HAPUS</button>";

        if($schedule->course->type === 1){
            $meetingAmount = $schedule->meeting_amount;
        }
        else{
            $meetingAmount = 'FULL';
        }

        if($schedule->is_expired){
            $dayleft = 0;
        }
        else{
            $dayleft = $schedule->day_left;
        }

        return[
            'customer_name'         => $schedule->customer->name,
            'customer_parent_name'  => $schedule->customer->parent_name,
            'course_name'           => $schedule->course->name,
            'status'                => $schedule->status->description,
            'meeting_amount'        => $meetingAmount,
            'day_left'              => $dayleft,
            'finish_date'           => $finishDate,
            'action'                => $action
        ];
    }
}