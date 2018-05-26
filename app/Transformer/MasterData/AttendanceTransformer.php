<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 23/05/2018
 * Time: 14:56
 */

namespace App\Transformer\MasterData;


use App\Models\Attendance;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class AttendanceTransformer extends TransformerAbstract
{
    public function transform(Attendance $attendance){

        $date = Carbon::parse($attendance->date)->format('d M Y, H:i');
//        $action = "<a class='btn btn-xs btn-info' href='attendances/show/".$attendance->id."' data-toggle='tooltip' data-placement='top'><i class='fa fa-eye'></i></a>";
//        $action = "<a class='btn btn-xs btn-info' href='attendances/edit/".$attendance->id."' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";
        $action = "<a class='btn btn-xs btn-info' href='#' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";

        return[
            'name'              => $attendance->Customer->name,
            'course_name'       => $attendance->Schedule->Course->name,
            'coach_name'       => $attendance->Schedule->Course->Coach->name,
            'meeting_number'    => $attendance->meeting_number,
            'date'              => $date,
            'action'            => $action
        ];
    }
}