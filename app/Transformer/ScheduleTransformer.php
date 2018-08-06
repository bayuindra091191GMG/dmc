<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 23/01/2018
 * Time: 10:45
 */

namespace App\Transformer;

use App\Models\Schedule;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class ScheduleTransformer extends TransformerAbstract
{
    public function transform(Schedule $schedule){

//        $createdDate = Carbon::parse($schedule->created_at)->format('d M Y');
        $createdDate = Carbon::parse($schedule->created_at)->toIso8601String();
        $startDate = Carbon::parse($schedule->start_date)->toIso8601String();
        $finishDate = Carbon::parse($schedule->finish_date)->toIso8601String();
        if($schedule->course->type == 4){
            $action =
                "<a class='btn btn-xs btn-info' href='schedules/".$schedule->id."/change' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";
//            $action .= "<a class='delete-modal btn btn-xs btn-danger' data-id='". $schedule->id ."' ><i class='fa fa-trash'></i></a>";

        }
        else{
            $action = "";
        }
        return[
            'customer_name'         => $schedule->customer->name,
            'customer_parent_name'  => $schedule->customer->parent_name ?? '-',
            'course_name'           => $schedule->course->name,
            'coach_name'            => $schedule->course->coach->name,
            'start_date'            => $startDate,
            'finish_date'           => $finishDate,
            'meeting_amount'        => $schedule->meeting_amount." / ".$schedule->course->meeting_amount,
            'month_amount'          => $schedule->month_amount,
            'status'                => $schedule->status->description,
            'created_at'            => $createdDate,
            'action'                => $action
        ];
    }
}
