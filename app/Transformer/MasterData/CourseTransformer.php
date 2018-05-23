<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 23/01/2018
 * Time: 10:45
 */

namespace App\Transformer\MasterData;

use App\Models\Course;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class CourseTransformer extends TransformerAbstract
{
    public function transform(Course $course){

        $createdDate = Carbon::parse($course->created_at)->format('d M Y');
        $name = "<a href='/admin/courses/show/" . $course->id. "' style='text-decoration: underline;'>". $course->name. "</a>";
        $action = "<a class='btn btn-xs btn-primary' href='courses/show/". $course->id."' data-toggle='tooltip' data-placement='top'><i class='fa fa-eye'></i></a>";
        $action .=
            "<a class='btn btn-xs btn-info' href='courses/".$course->id."/edit' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";
        $action .= "<a class='delete-modal btn btn-xs btn-danger' data-id='". $course->id ."' ><i class='fa fa-trash'></i></a>";

        $type = 'class';
        if($course->type == 1){
            $type = 'package';
        }

        return[
            'name'              => $name,
            'type'              => $type,
            'coach'             => $course->coach->name,
            'price'             => 'Rp '.$course->price_string,
            'meeting_amount'    => $course->meeting_amount,
            'status'            => $course->status->description,
            'created_at'        => $createdDate,
            'action'            => $action
        ];
    }
}
