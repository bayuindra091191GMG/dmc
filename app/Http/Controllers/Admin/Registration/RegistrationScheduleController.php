<?php


namespace App\Http\Controllers\Admin\Registration;


use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Customer;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class RegistrationScheduleController extends Controller
{
    public function formStepTwo(int $type, int $student_id){
        //dd($type .' & '. $student_id);

        if($type === 1){
            $courseType = 'MUAYTHAI';
            $viewPath = 'admin.registrations.schedules.step_2_schedule_muaythai';
            $backRoute = 'admin.registration.muaythai.step-one';
        }
        else if($type === 2){
            $courseType = 'DANCE';
            $viewPath = 'admin.registrations.schedules.step_2_schedule_dance';
            $backRoute = 'admin.registration.dance.step-one';
        }
        else if($type === 3){
            $courseType = 'PRIVATE';
            $viewPath = 'admin.registrations.schedules.step_2_schedule_private';
            $backRoute = 'admin.registration.private.step-one';
        }
        else if($type === 4){
            $courseType = 'GYMNASTIC';
            $viewPath = 'admin.registrations.schedules.step_2_schedule_gymnastic';
            $backRoute = 'admin.registration.gymnastic.step-one';
        }
        else{
            $courseType = 'INVALID';
            $viewPath = 'INVALID';
            $backRoute = 'INVALID';
            dd('INVALID COURSE TYPE!');
        }

        $student = Customer::find($student_id);
        if(empty($student)){
            dd('INVALID STUDENT!');
        }

        $data = [
            'type'          => $type,
            'courseType'    => $courseType,
            'student'       => $student,
            'backRoute'     => $backRoute
        ];

        return view($viewPath)->with($data);
    }

    public function store(Request $request)
    {
        // Validate details
        $customer = $request->input('customer_id');
        $courses = $request->input('course_id');
        $dayAdd = $request->input('day');
        $bonuses = $request->input('bonus');
        $valid = true;
        $validClass = true;

        $idx = 0;
        foreach($courses as $item){
            if(empty($item)) $valid = false;

            //Check if the customer already take that class
            $tmpCourse = Schedule::where('customer_id', $customer)
                ->where('course_id', $item)
                ->where('day', $dayAdd[$idx])
                ->where(function ($q) {
                    $q->where('status_id', 2)
                        ->orWhere('status_id', 3);
                })
                ->first();
            if($tmpCourse != null){
                $validClass = false;
            }
            $idx++;
        }

        if(!$valid){
            return redirect()->back()->withErrors("Kelas Wajib dipilih!");
        }

        if(!$validClass){
            return redirect()->back()->withErrors("Sudah ada Kelas yang diambil!");
        }

        $user = Auth::user();
        $now = Carbon::now('Asia/Jakarta');

        $i = 0;
        $dateTimeNow = Carbon::now('Asia/Jakarta');

//        $first_day_of_the_current_month = Carbon::today()->startOfMonth();
//        $last_day_of_the_current_month = $first_day_of_the_current_month->copy()->endOfMonth();

        foreach ($courses as $course){
            $courseData = Course::find($course);
            $meetingAmount = 0;
            if($courseData->type == 2 || $courseData->type == 4) {
//                $now = $dateTimeNow;
//                if($now->day < 10){
//                    $finish = Carbon::createFromFormat('Y-m-d', $now->year.'-'.$now->month.'-10');
//                }
//                else{
//                    $newMonth = $now->month + 1;
//                    if($newMonth > 12){
//                        $newYear = $now->year + 1;
//                        $finish = Carbon::createFromFormat('Y-m-d', $newYear.'-1-10');
//                    }
//                    else{
//                        $finish = Carbon::createFromFormat('Y-m-d', $now->year.'-'.$newMonth.'-10');
//                    }
//                }

                // Get next month date at 10th
                $nextMonthDate = $dateTimeNow->copy()->addMonthsNoOverflow(1);
                $month = $nextMonthDate->month;
                $year = $nextMonthDate->year;
                $finish = \Carbon\Carbon::create($year, $month, 10, 0, 0, 0);
            }
            else{
                $meetingAmount = $courseData->meeting_amount;
//                if($courseData->id === 3 || $courseData->id === 4){
//                    $meetingAmount = $courseData->meeting_amount + 3;
//                }

                $bonusAmount = intval($bonuses[$i]);
                $meetingAmount += $bonusAmount;

                $finish = Carbon::now('Asia/Jakarta');
                $finish->addDays($courseData->valid);
            }

            $scheduleDb = Schedule::where('customer_id', $request->get('customer_id'))
                ->where('course_id', $course)
                ->where('status_id', 2)
                ->first();

            if($courseData->type == 4 && $courseData->meeting_amount == 8 && !empty($scheduleDb)){
                $selectedDay = $scheduleDb->day;
                if(strpos($selectedDay, $dayAdd[$i]) === false){
                    $selectedDay .= " & ". $dayAdd[$i];
                    $scheduleDb->day = $selectedDay;
                    $scheduleDb->save();
                }
            }
            else{
                Schedule::create([
                    'customer_id'       => $request->input('customer_id'),
                    'course_id'         => $course,
                    'day'               => $dayAdd[$i],
                    'start_date'        => $dateTimeNow->toDateTimeString(),
                    'finish_date'       => $finish->toDateTimeString(),
                    'meeting_amount'    => $meetingAmount,
                    'month_amount'      => 1,
                    'status_id'         => 2,
                    'created_by'        => $user->id,
                    'created_at'        => $now->toDateTimeString(),
                    'updated_by'        => $user->id,
                    'updated_at'        => $now->toDateTimeString()
                ]);
            }

            $i++;
        }

//        Session::flash('message', 'Berhasil membuat jadwal baru!');
        return redirect()->route('admin.registration.step-three', ['type' => $request->input('type'), 'student_id' => $request->input('customer_id')]);
    }

}