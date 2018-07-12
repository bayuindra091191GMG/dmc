<?php

namespace App\Http\Controllers\Admin;

use App\Models\Coach;
use App\Models\Course;
use App\Models\Customer;
use App\Models\Schedule;
use App\Models\TransactionDetail;
use App\Transformer\ScheduleTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
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
        $schedules = Schedule::all();
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
                ->where('status_id', 3)
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

        //Get All Data and store
        $i = 0;
        $dateTimeNow = Carbon::now('Asia/Jakarta');
        foreach ($courses as $course){
            $courseData = Course::find($course);

            if($courseData->type == 2) {
                $finish = $dateTimeNow;
                $finish->addDays(30);
            }
            else{
                $finish = $dateTimeNow;
                $finish->addDays($courseData->valid);
            }

            Schedule::create([
                'customer_id'       => $request->get('customer_id'),
                'course_id'         => $course,
                'day'               => $dayAdd[$i],
                'start_date'        => $dateTimeNow->toDateTimeString(),
                'finish_date'       => $finish->toDateTimeString(),
                'meeting_amount'    => $courseData->meeting_amount,
                'month_amount'      => 1,
                'status_id'         => 3
            ]);

            $i++;
        }

        Session::flash('message', 'Berhasil membuat data Jadwal baru!');
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
            'dayTime'      => $dayTime
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
        $courseDB = Course::find($request->get('course_add'));
        if($courseDB->type == 1) return redirect()->back()->withErrors("Kelas Wajib dipilih!");

        $dateTimeNow = Carbon::now('Asia/Jakarta');

        $schedule->day = $request->get('day_add');
        $schedule->course_id = $request->get('course_add');
        $schedule->updated_at = $dateTimeNow;
        $schedule->save();

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
                $schedules = Schedule::where('customer_id', $customerId)
                    ->where('status_id', 3)
                    ->whereHas('course', function ($query) use ($term){
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
}
