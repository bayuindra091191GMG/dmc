<?php

namespace App\Http\Controllers\Admin;

use App\Models\Attendance;
use App\Models\Coach;
use App\Models\Course;
use App\Models\Customer;
use App\Models\Schedule;
use App\Models\TransactionDetail;
use App\Transformer\ScheduleTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('admin.schedules.index');
    }


    //DataTables

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function anyData()
    {
        $schedules = Schedule::join('customers', 'schedules.customer_id', '=', 'customers.id')->orderBy('customers.name')
            ->select('schedules.*')
            ->get();
        return DataTables::of($schedules)
            ->setTransformer(new ScheduleTransformer())
            ->addIndexColumn()
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.schedules.create', compact('customers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate details
        $customer = $request->get('customer_id');
        $courses = $request->input('course_id');
        $dayAdd = $request->get('day');
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

        if($customer == null){
            return redirect()->back()->withErrors("Pilih Murid terlebih dahulu!");
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
                if($courseData->id === 3 || $courseData->id === 4){
                    $meetingAmount = $courseData->meeting_amount + 3;
                }

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
                    'customer_id'       => $request->get('customer_id'),
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

        Session::flash('message', 'Berhasil membuat jadwal baru!');
        return redirect()->route('admin.schedules');
    }

    /**
     * Display the specified resource.
     *
     * @param Schedule $schedule
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Schedule $schedule)
    {
        return view('admin.schedules.show', ['schedule' => $schedule]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Coach $coach
     * @return \Illuminate\Http\Response
     */
    public function edit(Schedule $schedule)
    {
        $course = Course::find($schedule->course_id);

        $days = preg_split('@;@', $course->day, NULL, PREG_SPLIT_NO_EMPTY);
        $hours = preg_split('@;@', $course->hour, NULL, PREG_SPLIT_NO_EMPTY);
        $date = Carbon::parse($schedule->finish_date)->format('d M Y');
        $dayTime = array();

        $i = 0;
        foreach ($days as $day){
            if(!empty($course->hour))
            {
                array_push($dayTime,$day . '-' . $hours[$i]);
                $i++;
            }
            else{
                array_push($dayTime,$day);
                $i++;
            }
        }

        $data = [
            'schedule'      => $schedule,
            'course'      => $course,
            'dayTime'      => $dayTime,
            'date'      => $date
        ];
//        dd($data);
        return view('admin.schedules.edit')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Coach $coach
     * @return mixed
     */
    public function update(Request $request, Schedule $schedule)
    {
        //dd($schedule);
        $courseDB = Course::find($request->get('course_add'));
//        if($courseDB->type == 1) return redirect()->back()->withErrors("Kelas Wajib dipilih!");

        $dateTimeNow = Carbon::now('Asia/Jakarta');

        //$date = Carbon::createFromFormat('d M Y', $request->input('finish_date'), 'Asia/Jakarta');

        //if course type = class
//        dd($courseDB->type);
        if($courseDB->type == 2 || $courseDB->type == 4){
            $schedule->day = $request->get('day_add');
            $schedule->course_id = $request->get('course_add');
            $schedule->updated_at = $dateTimeNow;
//            $schedule->finish_date = $date->toDateTimeString();
            $schedule->save();
        }
        else{
            $schedule->updated_at = $dateTimeNow;
           // $schedule->finish_date = $date->toDateTimeString();
            $schedule->save();
        }

        Session::flash('message', 'Berhasil mengubah data Jadwal!');

        return redirect()->route('admin.customers.show', ['customer' => $schedule->customer_id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function destroy(Request $request)
    {
        try{
            $coach = Coach::find($request->input('id'));

            //Check first if Trainer already in Transaction
            $classes = Course::where('coach_id', $coach->id)->get();
            if($classes != null){
                foreach ($classes as $data){
                    $transaction = TransactionDetail::where('class_id', $data->id)->get();
                    if($transaction != null){
                        Session::flash('error', 'Data Trainer '. $coach->name . ' Tidak dapat dihapus karena masih wajib mengajar!');
                        return Response::json(array('success' => 'VALID'));
                    }
                }
            }
            $coach->delete();

            Session::flash('message', 'Berhasil menghapus data Trainer '. $coach->name);
            return Response::json(array('success' => 'VALID'));
        }
        catch(\Exception $ex){
            return Response::json(array('errors' => 'INVALID'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Coach $coach
     * @return \Illuminate\Http\Response
     */
    public function change(Schedule $schedule)
    {
        $course = Course::find($schedule->course_id);
        $dateTimeNow = Carbon::now('Asia/Jakarta');

        $date = Carbon::parse($dateTimeNow)->format('d M Y hh:mm');

        $data = [
            'schedule'      => $schedule,
            'course'      => $course,
            'date'      => $date
        ];
//        dd($data);
        return view('admin.schedules.changes.edit')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Schedule $schedule
     * @return mixed
     */
    public function updateChange(Request $request, Schedule $schedule)
    {
        $dateTimeNow = Carbon::now('Asia/Jakarta');
        $date = Carbon::createFromFormat('d M Y H:i', $request->input('date'), 'Asia/Jakarta');

        //save the attendance
        $attendanceCount = Attendance::where('customer_id', $schedule->customer_id)->where('schedule_id', $schedule->id)->count();

        if($attendanceCount == 4){
            return redirect()->back()->withErrors("Absensi Pada Kelas ini telah 4 kali!");
        }
        else{
            $user = Auth::user();
            $attendanceCount++;
            $attendance = Attendance::create([
                'customer_id'           => $schedule->customer_id,
                'schedule_id'           => $schedule->id,
                'date'                  => $date->toDateTimeString(),
                'meeting_number'        => $attendanceCount,
                'status_id'             => 2,
                'created_by'            => $user->id,
                'created_at'            => $dateTimeNow->toDateTimeString()
            ]);
            $attendance->save();

            Session::flash('message', 'Berhasil mengubah data Jadwal!');

            return redirect()->route('admin.schedules');
        }
    }

    public function stop(Request $request){
        try{
            $now = Carbon::now('Asia/Jakarta');
            $user = Auth::user();
            $schedule = Schedule::find($request->input('schedule_id'));
            if(empty($schedule)) return Response::json(array('errors' => 'EMPTY'));

            if($schedule->status_id === 6) return Response::json(array('errors' => 'STOPPED'));

            $schedule->status_id = 6;
            $schedule->updated_at = $now->toDateTimeString();
            $schedule->updated_by = $user->id;
            $schedule->save();

            Session::flash('message', 'Berhasil membatalkan jadwal kelas'. $schedule->course->name);

            return Response::json(array('success' => 'VALID'));
        }
        catch(\Exception $ex){
            error_log($ex);
            return Response::json(array('errors' => 'INVALID'));
        }
    }

    public function getSchedules(Request $request){
        $term = trim($request->q);

        $courseType = 0;
        if(!empty($request->course_type)){
            $courseType = (int) $request->course_type;
        }

        $schedules = null;
        if(!empty($request->customer)) {
            $customerId = $request->customer;



            if(Customer::where('id', $customerId)->exists()){

                if($courseType === 3){
                    $schedules = Schedule::where('customer_id', $customerId)
                        ->whereIn('status_id', [2,3])
                        ->whereHas('course', function ($query) use ($term){
                            $query->where('name','LIKE', '%'. $term. '%')
                                ->where('type', 3);
                        })->get();
                }
                else{
                    $schedules = Schedule::where('customer_id', $customerId)
                        ->where('status_id', 2)
                        ->whereHas('course', function ($query) use ($term){
                            $query->where('name','LIKE', '%'. $term. '%');
                        });

                    if($courseType !== 0){
                        $schedules = $schedules->whereHas('course', function ($query) use ($courseType){
                            $query->where('type', $courseType);
                        })->get();
                    }
                    else{
                        $schedules = $schedules->whereHas('course', function ($query){
                            $query->where('type', '!=', 3);})->get();
                    }
                }
            }
        }
        else{
            $schedules = Schedule::whereHas('courses', function ($query) use ($term){
                    $query->where('name','LIKE', '%'. $term. '%');
                });

            if($courseType !== 0){
                $schedules = $schedules->whereHas('course', function ($query) use ($courseType){
                    $query->where('type', $courseType);
                })->get();
            }
            else{
                $schedules = $schedules->get();
            }
        }

        $formatted_tags = [];

        foreach ($schedules as $schedule) {
            $formatted_tags[] = ['id' => $schedule->id. '#'. $schedule->course->name. '#'. $schedule->course->coach->name. '#'. $schedule->day. '#'. $schedule->course->price, 'text' => $schedule->course->name];
        }

        return Response::json($formatted_tags);
    }

    public function getScheduleProrates(Request $request){
        $term = trim($request->q);

        $courseType = 0;
        if(!empty($request->course_type)){
            $courseType = (int) $request->course_type;
        }

        $schedules = null;
        if(!empty($request->customer)) {
            $customerId = $request->customer;
            if(Customer::where('id', $customerId)->exists()){
                $schedules = Schedule::where('customer_id', $customerId)
                    ->where('status_id', 2)
                    ->whereHas('course', function ($query) use ($term){
                        $query->where('name','LIKE', '%'. $term. '%');
                    });

                if($courseType !== 0){
                    $schedules = $schedules->whereHas('course', function ($query) use ($courseType){
                        $query->whereIn('type', [2, 4]);
                    })->get();
                }
                else{
                    $schedules = $schedules->get();
                }
            }
        }
        else{
            $schedules = Schedule::whereHas('courses', function ($query) use ($term){
                    $query->where('name','LIKE', '%'. $term. '%');
                });

            if($courseType !== 0){
                $schedules = $schedules->whereHas('course', function ($query) use ($courseType){
                    $query->where('type', $courseType);
                })->get();
            }
            else{
                $schedules = $schedules->get();
            }
        }

        $formatted_tags = [];

        foreach ($schedules as $schedule) {
            $formatted_tags[] = ['id' => $schedule->id. '#'. $schedule->course->name. '#'. $schedule->course->coach->name. '#'. $schedule->day. '#'. $schedule->course->price. '#'. $schedule->course->meeting_amount, 'text' => $schedule->course->name];
        }

        return Response::json($formatted_tags);
    }
    public function getScheduleCuti(Request $request){
        $term = trim($request->q);
        $schedules = null;
        if(!empty($request->customer)) {
            $customerId = $request->customer;

            if(Customer::where('id', $customerId)->exists()){

                $schedules = Schedule::where('customer_id', $customerId)
                    ->where('status_id', 3)
                    ->whereHas('course', function ($query) use ($term){
                        $query->where('name','LIKE', '%'. $term. '%')
                            ->where('type', 2);
                    })->get();
            }
        }

        $formatted_tags = [];

        foreach ($schedules as $schedule) {
            $formatted_tags[] = ['id' => $schedule->id. '#'. $schedule->course->name. '#'. $schedule->course->coach->name. '#'. $schedule->day. '#'. $schedule->course->price, 'text' => $schedule->course->name];
        }

        return Response::json($formatted_tags);
    }
}
