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
//    protected $type;
//    public function __construct($type)
//    {
//        $this->type = $type;
//    }

    public function transform(Course $course){
        $createdDate = Carbon::parse($course->created_at)->format('d M Y');
        $name = "<a href='/admin/courses/show/" . $course->id. "' style='text-decoration: underline;'>". $course->name. "</a>";

        //if type is normal show
//        if($course->type == 1) {
//            $action = "<a class='btn btn-xs btn-primary' href='courses/show/" . $course->id . "' data-toggle='tooltip' data-placement='top'><i class='fa fa-eye'></i></a>";
//            $action .=
//                "<a class='btn btn-xs btn-info' href='courses/" . $course->id . "/edit' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";
//            $action .= "<a class='delete-modal btn btn-xs btn-danger' data-id='" . $course->id . "' ><i class='fa fa-trash'></i></a>";
//        }
//        else{
//            $action = "<a class='btn btn-xs btn-primary' href='show-this-day/" . $course->id . "' data-toggle='tooltip' data-placement='top'><i class='fa fa-eye'></i></a>";
//        }

        $courseShowUrl = route('admin.courses.show', ['course' => $course]);
        $courseEditUrl = route('admin.courses.edit', ['course' => $course]);

        $action = "<a class='btn btn-xs btn-primary' href='". $courseShowUrl ."' data-toggle='tooltip' data-placement='top'><i class='fa fa-eye'></i></a>";
        $action .= "<a class='btn btn-xs btn-info' href='". $courseEditUrl ."' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";
//        $action .= "<a class='delete-modal btn btn-xs btn-danger' data-id='" . $course->id . "' ><i class='fa fa-trash'></i></a>";

        if($course->type == 1){
            $type = 'Muaythai';
        }
        elseif($course->type == 2){
            $type = 'Dance';
        }
        elseif($course->type == 3){
            $type = 'Gymnastic';
        }
        else{
            $type = 'Private';
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
