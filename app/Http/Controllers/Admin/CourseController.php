<?php

namespace App\Http\Controllers\Admin;

use App\Models\Attendance;
use App\Models\Auth\User\User;
use App\Models\Coach;
use App\Models\Course;
use App\Models\Customer;
use App\Models\Schedule;
use App\Models\TransactionHeader;
use App\Transformer\MasterData\CourseTransformer;
use App\Transformer\MasterData\CustomerTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('admin.courses.index');
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
        $courses = Course::all();
        return DataTables::of($courses)
            ->setTransformer(new CourseTransformer(1))
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
        $coaches = Coach::where('id', '!=', 0)->get();
        return view('admin.courses.create', compact('coaches'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'              => 'required|max:50',
            'price'             => 'required'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors())->withInput($request->all());

        if($request->input('type') === '-1'){
            return redirect()->back()->withErrors('Pilih Tipe Kelas', 'default')->withInput($request->all());
        }

        $selectedDays = "";
        $selectedHours = "";
        if($request->input('type') == 2){
            $days = $request->get('chk');
            $hours = $request->get('hour');
            if($days == null){
                return redirect()->back()->withErrors('Belum ada hari yang dipilih', 'default')->withInput($request->all());
            }
            if($hours == null){
                return redirect()->back()->withErrors('Belum ada jam yang dipilih', 'default')->withInput($request->all());
            }
            foreach ($days as $day){
                $selectedDays.=$day.";";
            }
            foreach ($hours as $hour){
                $selectedHours.=$hour.";";
            }
        }
        if($request->input('type') == 3){
            if($request->input('coach_id') === '-1'){
                return redirect()->back()->withErrors('Pilih Trainer', 'default')->withInput($request->all());
            }
        }
//dd($request);
        $meetingAmounts = 4;
        $validAmount = 0;

        //Package
        if($request->get('type') == 1){
            $meetingAmounts = $request->get('meeting_amount');
            $trainer = 0;
            $validAmount = $request->get('valid');
            $hour = "NONE";
        }
        else if($request->get('type') == 3){
            $meetingAmounts = 0;
            $trainer = $request->get('coach_id');
            $days = $request->get('chk');
            $hours = $request->get('hour');
            if($days != null && $hours != null){
                foreach ($days as $day){
                    $selectedDays.=$day.";";
                }
                foreach ($hours as $hour){
                    $selectedHours.=$hour.";";
                }
            }
        }
        else{
            $trainer = $request->get('coach_id');
        }

        $price = str_replace('.','', $request->get('price'));

        Course::create([
            'name'              => $request->get('name'),
            'type'              => $request->get('type'),
            'price'             => $price,
            'coach_id'          => $trainer,
            'meeting_amount'    => $meetingAmounts,
            'valid'             => $validAmount,
            'day'               => $selectedDays,
            'hour'              => $selectedHours,
            'status_id'         => 1
        ]);

        Session::flash('message', 'Berhasil membuat data Kelas baru!');

        return redirect()->route('admin.courses');
    }

    /**
     * Display the specified resource.
     *
     * @param Course $course
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Course $course)
    {
        if($course->type == 1 || $course->type == 3){
            $days[0] = "Bebas";
            $hours[0] = "Bebas";
        }
        else {
            $days = preg_split('@;@', $course->day, NULL, PREG_SPLIT_NO_EMPTY);
            $hours = preg_split('@;@', $course->hour, NULL, PREG_SPLIT_NO_EMPTY);
        }

        //Get customer/murid
        $customers = Schedule::where('course_id', $course->id)->get();
        return view('admin.courses.show', ['course' => $course, 'days' => $days, 'hours' => $hours, 'customers' => $customers]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Course $course
     * @return \Illuminate\Http\Response
     */
    public function edit(Course $course)
    {
        $coaches = Coach::all();
        return view('admin.courses.edit',compact('course', 'coaches'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Course $course
     * @return mixed
     */
    public function update(Request $request, Course $course)
    {
        $validator = Validator::make($request->all(), [
            'name'              => 'required|max:50',
            'price'             => 'required',
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());

        $dateTimeNow = Carbon::now('Asia/Jakarta');

        if($request->input('type') === '-1'){
            return redirect()->back()->withErrors('Pilih Tipe Kelas', 'default')->withInput($request->all());
        }

        $selectedDays = "";
        if($request->input('type') != 1){
            $days = $request->get('chk');
            if($days == null){
                return redirect()->back()->withErrors('Belum ada hari yang dipilih', 'default')->withInput($request->all());
            }
            foreach ($days as $day){
                $selectedDays.=$day.";";
            }
        }

        $course->name = $request->get('name');
        $course->type = $request->get('type');
        $course->price = $request->get('price');
        $course->coach_id = $request->get('coach_id');
        $course->meeting_amount = $request->get('meeting_amount');
        $course->day = $selectedDays;
        $course->updated_at = $dateTimeNow;
        $course->save();

        Session::flash('message', 'Berhasil mengubah data Kelas!');

        return redirect()->route('admin.courses.edit', ['course' => $course]);
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
            $course = Course::find($request->input('id'));

            //Check if course already in Transactions
            $schedule = Schedule::where('course_id', $course->id)->get();
            if($schedule != null && $schedule->count() != 0){
                Session::flash('error', 'Data Kelas '. $course->name . ' Tidak dapat dihapus karena masih memiliki Jadwal!');
                return Response::json(array('success' => 'VALID'));
            }

            $course->delete();

            Session::flash('message', 'Berhasil menghapus data Kelas '. $course->name);
            return Response::json(array('success' => 'VALID'));
        }
        catch(\Exception $ex){
            return Response::json(array('errors' => 'INVALID'));
        }
    }

    public function getCourses(Request $request){
        $term = trim($request->q);
        $courses= Course::where('name', 'LIKE', '%'. $term. '%')
            ->get();

        $formatted_tags = [];

        foreach ($courses as $course) {
            if($course->type == 1){
                $courseType = "Package";
            }
            else if($course->type == 2){
                $courseType = "Class";
            }
            else{
                $courseType = "Private";
            }
            $formatted_tags[] = ['id' => $course->id, 'text' => $course->name.'('.$courseType. ') - '. $course->coach->name];
        }

        return \Response::json($formatted_tags);
    }

    public function getCoursesPackage(Request $request){
        $term = trim($request->q);
        $courses= Course::where('name', 'LIKE', '%'. $term. '%')
            ->where('type', 1)
            ->get();

        $formatted_tags = [];

        foreach ($courses as $course) {
            if($course->type == 1){
                $courseType = "Package";
            }
            else if($course->type == 2){
                $courseType = "Class";
            }
            else{
                $courseType = "Private";
            }
            $formatted_tags[] = ['id' => $course->id, 'text' => $course->name];
        }

        return \Response::json($formatted_tags);
    }

    public function getDays(Request $request){
        try{

            $id = Input::get('id');
            $course = Course::where('id', $id)->first();
            $data = array();

            if($course->type == 1){
                $days[0] = "Bebas";
                array_push($data,"Bebas");
            }
            else if($course->type == 2){
                $days = preg_split('@;@', $course->day, NULL, PREG_SPLIT_NO_EMPTY);
                $hours = preg_split('@;@', $course->hour, NULL, PREG_SPLIT_NO_EMPTY);
                $i = 0;
                foreach ($days as $day){
                    if(!empty($course->hour))
                    {
                        array_push($data,$day . '-' . $hours[$i]);
                        $i++;
                    }
                    else{
                        array_push($data,$day);
                        $i++;
                    }
                }
            }
            else{
                if($course->day != null && $course->hour != null){
                    $days = preg_split('@;@', $course->day, NULL, PREG_SPLIT_NO_EMPTY);
                    $hours = preg_split('@;@', $course->hour, NULL, PREG_SPLIT_NO_EMPTY);
                    $i = 0;
                    foreach ($days as $day){
                        if(!empty($course->hour))
                        {
                            array_push($data,$day . ' - ' . $hours[$i]);
                            $i++;
                        }
                        else{
                            array_push($data,$day);
                            $i++;
                        }
                    }
                }
                else{
                    $days[0] = "Bebas";
                    array_push($data,"Bebas");
                }
            }

            return \Response::json($data);
        }
        catch(\Exception $ex){
            return error_log($ex);
        }
    }

    public function thisDayCourses(){
        return view('admin.courses.thisday');
    }

    public function getThisDayCourses(){
        $now = Carbon::now('Asia/Jakarta');
        $day = $now->format('l');

        //Convert Day from English to Indonesian
        switch ($day){
            case 'Monday': $dayQuery = 'Senin';
                            break;
            case 'Tuesday': $dayQuery = 'Selasa';
                            break;
            case 'Wednesday': $dayQuery = 'Rabu';
                break;
            case 'Thursday': $dayQuery = 'Kamis';
                break;
            case 'Friday': $dayQuery = 'Jumat';
                break;
            case 'Saturday': $dayQuery = 'Sabtu';
                break;
            case 'Sunday': $dayQuery = 'Minggu';
                break;
            default: $dayQuery = 'Senin';
                break;
        }

        $courses = Course::where('type', 2)->where('day', 'LIKE', '%'. $dayQuery . '%')->get();
        return DataTables::of($courses)
            ->setTransformer(new CourseTransformer(2))
            ->addIndexColumn()
            ->make(true);
    }

    /**
     * Display the specified resource.
     *
     * @param Course $course
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showThisDay(Course $course)
    {
        if($course->type == 1){
            $days[0] = "Bebas";
            $hours[0] = "Bebas";
        }
        else {
            $days = preg_split('@;@', $course->day, NULL, PREG_SPLIT_NO_EMPTY);
            $hours = preg_split('@;@', $course->hour, NULL, PREG_SPLIT_NO_EMPTY);
        }

        //Get murid yang sudah hadir hari ini
        $now = Carbon::now('Asia/Jakarta');
        $day = $now->format('l');

        //Convert Day from English to Indonesian
        switch ($day){
            case 'Monday': $dayQuery = 'Senin';
                break;
            case 'Tuesday': $dayQuery = 'Selasa';
                break;
            case 'Wednesday': $dayQuery = 'Rabu';
                break;
            case 'Thursday': $dayQuery = 'Kamis';
                break;
            case 'Friday': $dayQuery = 'Jumat';
                break;
            case 'Saturday': $dayQuery = 'Sabtu';
                break;
            case 'Sunday': $dayQuery = 'Minggu';
                break;
            default: $dayQuery = 'Senin';
                break;
        }

        //Get Schedule yang berkaitan
        $schedule = Schedule::where('course_id', $course->id)->where('day', 'LIKE', '%'. $dayQuery . '%')->first();

        if($schedule != null)
        {
            $customers = Attendance::where('schedule_id', $schedule->id)->whereDate('created_at', Carbon::today())->get();
        }
        else{
            $customers = null;
        }
        return view('admin.courses.thisdayshow', ['course' => $course, 'days' => $days, 'hours' => $hours, 'customers' => $customers]);
    }
}
