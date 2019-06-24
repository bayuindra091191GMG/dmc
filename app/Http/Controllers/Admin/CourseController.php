<?php

namespace App\Http\Controllers\Admin;

use App\Models\Attendance;
use App\Models\Auth\User\User;
use App\Models\Coach;
use App\Models\Course;
use App\Models\Customer;
use App\Models\Day;
use App\Models\Hour;
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

        $baby = $request->input('is_baby');
        $twicePerWeek = $request->input('twice_week');
        if($request->input('type') != 2 && $baby != null){
            return redirect()->back()->withErrors('Hanya tipe kelas Class untuk Bayi', 'default')->withInput($request->all());
        }
        if($request->input('type') != 4 && $twicePerWeek != null){
            return redirect()->back()->withErrors('Hanya tipe kelas Gymnastic untuk 2 kali seminggu', 'default')->withInput($request->all());
        }

        //check for class and gymnastic
        $days = $request->input('chk');
        if($request->input('type') == 2 || $request->input('type') == 4){

            if($request->input('coach_id') === '-1'){
                return redirect()->back()->withErrors('Belum ada Trainer yang dipilih', 'default')->withInput($request->all());
            }
            if(empty($days) ){
                return redirect()->back()->withErrors('Belum ada hari yang dipilih', 'default')->withInput($request->all());
            }
        }

        if($request->input('type') == 3){
            if($request->input('coach_id') === '-1'){
                return redirect()->back()->withErrors('Pilih Trainer', 'default')->withInput($request->all());
            }
        }

        if($baby == null && $twicePerWeek == null){
            $meetingAmounts = 4;
        }
        else{
            $meetingAmounts = 8;
        }
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
        }
        else{
            $trainer = $request->get('coach_id');
        }

        $price = str_replace('.','', $request->get('price'));

        $newCourse = Course::create([
            'name'              => $request->get('name'),
            'type'              => $request->get('type'),
            'price'             => $price,
            'coach_id'          => $trainer,
            'meeting_amount'    => $meetingAmounts,
            'valid'             => $validAmount,
            'status_id'         => 1
        ]);

        //Save Day and Hour if type == 2 or 3
        if(($request->input('type') == 2 || $request->input('type') == 4 || $request->input('type') == 3)
            && !empty($days)){
            $days = $request->get('chk');
            foreach($days as $day){
                $newDay = Day::create([
                    'course_id'     => $newCourse->id,
                    'day_string'    => $day
                ]);

                //Save Hour
                switch ($day){
                    case 'Senin':
                        Hour::create([
                            'day_id'        => $newDay->id,
                            'hour_string'   => $request->input('hourMonday1')
                        ]);
                        if($request->input('hourMonday2') != null){
                            Hour::create([
                                'day_id'        => $newDay->id,
                                'hour_string'   => $request->input('hourMonday2')
                            ]);
                        }
                        break;
                    case 'Selasa':
                        Hour::create([
                            'day_id'        => $newDay->id,
                            'hour_string'   => $request->input('hourTuesday1')
                        ]);
                        if($request->input('hourTuesday2') != null){
                            Hour::create([
                                'day_id'        => $newDay->id,
                                'hour_string'   => $request->input('hourTuesday2')
                            ]);
                        }
                        break;
                    case 'Rabu':
                        Hour::create([
                            'day_id'        => $newDay->id,
                            'hour_string'   => $request->input('hourWednesday1')
                        ]);
                        if($request->input('hourWednesday2') != null){
                            Hour::create([
                                'day_id'        => $newDay->id,
                                'hour_string'   => $request->input('hourWednesday2')
                            ]);
                        }
                        break;
                    case 'Kamis':
                        Hour::create([
                            'day_id'        => $newDay->id,
                            'hour_string'   => $request->input('hourThursday1')
                        ]);
                        if($request->input('hourThursday2') != null){
                            Hour::create([
                                'day_id'        => $newDay->id,
                                'hour_string'   => $request->input('hourThursday2')
                            ]);
                        }
                        break;
                    case 'Jumat':
                        Hour::create([
                            'day_id'        => $newDay->id,
                            'hour_string'   => $request->input('hourFriday1')
                        ]);
                        if($request->input('hourFriday2') != null){
                            Hour::create([
                                'day_id'        => $newDay->id,
                                'hour_string'   => $request->input('hourFriday2')
                            ]);
                        }
                        break;
                    case 'Sabtu':
                        Hour::create([
                            'day_id'        => $newDay->id,
                            'hour_string'   => $request->input('hourSaturday1')
                        ]);
                        if($request->input('hourSaturday2') != null){
                            Hour::create([
                                'day_id'        => $newDay->id,
                                'hour_string'   => $request->input('hourSaturday2')
                            ]);
                        }
                        break;
                    case 'Minggu':
                        Hour::create([
                            'day_id'        => $newDay->id,
                            'hour_string'   => $request->input('hourSunday1')
                        ]);
                        if($request->input('hourSunday2') != null){
                            Hour::create([
                                'day_id'        => $newDay->id,
                                'hour_string'   => $request->input('hourSunday2')
                            ]);
                        }
                        break;
                }
            }
        }

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
        $courseSchedule = Day::where('course_id', $course->id)->count();
        if($course->type == 1 || ($course->type == 3 && $courseSchedule == 0)){
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
        if($course->type == 2){
            $days = preg_split('@;@', $course->day, NULL, PREG_SPLIT_NO_EMPTY);
            $hours = preg_split('@;@', $course->hour, NULL, PREG_SPLIT_NO_EMPTY);
            return view('admin.courses.edit',compact('course', 'coaches', 'days', 'hours'));
        }
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

        if($request->input('type') == 3){
            if($request->input('coach_id') === '-1'){
                return redirect()->back()->withErrors('Pilih Trainer', 'default')->withInput($request->all());
            }
        }
        $price = str_replace('.','', $request->get('price'));

        $course->name = $request->get('name');
        $course->type = $request->get('type');
        $course->price = $price;
        $course->coach_id = $request->get('coach_id');
        $course->meeting_amount = $request->get('meeting_amount');
        $course->updated_at = $dateTimeNow;
        $course->status_id = $request->get('status');
        $course->save();

        //Delete Days and Hours
        //Delete Hours
        $daysDelete = Day::where('course_id', $course->id)->get();
        foreach($daysDelete as $dayDelete){
            $hoursDelete = Hour::where('day_id', $dayDelete->id)->get();
            foreach ($hoursDelete as $hourDelete){
                $hourDelete->delete();
            }
        }

        foreach($daysDelete as $dayDelete){
            $dayDelete->delete();
        }

        $days = $request->get('chk');
        //Save Day and Hour if type == 2 or 3
        if(($request->input('type') == 2 || $request->input('type') == 4 || $request->input('type') == 3)
            && !empty($days)){
            $days = $request->get('chk');

            foreach($days as $day){
                $newDay = Day::create([
                    'course_id'     => $course->id,
                    'day_string'    => $day
                ]);

                //Save Hour
                switch ($day){
                    case 'Senin':
                        Hour::create([
                            'day_id'        => $newDay->id,
                            'hour_string'   => $request->input('hourMonday1')
                        ]);
                        if($request->input('hourMonday2') != null){
                            Hour::create([
                                'day_id'        => $newDay->id,
                                'hour_string'   => $request->input('hourMonday2')
                            ]);
                        }
                        break;
                    case 'Selasa':
                        Hour::create([
                            'day_id'        => $newDay->id,
                            'hour_string'   => $request->input('hourTuesday1')
                        ]);
                        if($request->input('hourTuesday2') != null){
                            Hour::create([
                                'day_id'        => $newDay->id,
                                'hour_string'   => $request->input('hourTuesday2')
                            ]);
                        }
                        break;
                    case 'Rabu':
                        Hour::create([
                            'day_id'        => $newDay->id,
                            'hour_string'   => $request->input('hourWednesday1')
                        ]);
                        if($request->input('hourWednesday2') != null){
                            Hour::create([
                                'day_id'        => $newDay->id,
                                'hour_string'   => $request->input('hourWednesday2')
                            ]);
                        }
                        break;
                    case 'Kamis':
                        Hour::create([
                            'day_id'        => $newDay->id,
                            'hour_string'   => $request->input('hourThursday1')
                        ]);
                        if($request->input('hourThursday2') != null){
                            Hour::create([
                                'day_id'        => $newDay->id,
                                'hour_string'   => $request->input('hourThursday2')
                            ]);
                        }
                        break;
                    case 'Jumat':
                        Hour::create([
                            'day_id'        => $newDay->id,
                            'hour_string'   => $request->input('hourFriday1')
                        ]);
                        if($request->input('hourFriday2') != null){
                            Hour::create([
                                'day_id'        => $newDay->id,
                                'hour_string'   => $request->input('hourFriday2')
                            ]);
                        }
                        break;
                    case 'Sabtu':
                        Hour::create([
                            'day_id'        => $newDay->id,
                            'hour_string'   => $request->input('hourSaturday1')
                        ]);
                        if($request->input('hourSaturday2') != null){
                            Hour::create([
                                'day_id'        => $newDay->id,
                                'hour_string'   => $request->input('hourSaturday2')
                            ]);
                        }
                        break;
                    case 'Minggu':
                        Hour::create([
                            'day_id'        => $newDay->id,
                            'hour_string'   => $request->input('hourSunday1')
                        ]);
                        if($request->input('hourSunday2') != null){
                            Hour::create([
                                'day_id'        => $newDay->id,
                                'hour_string'   => $request->input('hourSunday2')
                            ]);
                        }
                        break;
                }
            }
        }

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

            //Delete Days and Hours
            //Delete Hours
            $daysDelete = Day::where('course_id', $course->id)->get();
            foreach($daysDelete as $dayDelete){
                $hoursDelete = Hour::where('day_id', $dayDelete->id)->get();
                foreach ($hoursDelete as $hourDelete){
                    $hourDelete->delete();
                }
            }

            foreach($daysDelete as $dayDelete){
                $dayDelete->delete();
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
            else if($course->type == 4){
                $courseType = "Gymnastic";
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

    public function getExtendedCourses(Request $request){
        $term = trim($request->q);
        $courses= Course::where('name', 'LIKE', '%'. $term. '%')
            ->get();

        $formatted_tags = [];

        foreach ($courses as $course) {
            if($course->type == 1){
                $courseType = "Muaythai";
            }
            else if($course->type == 2){
                $courseType = "Dance";
            }
            else if($course->type == 4){
                $courseType = "Gymnastic";
            }
            else{
                $courseType = "Private";
            }

            $value = $course->id. '#'. $course->name. '#'. $course->coach->name. '#'. $course->price;

            $formatted_tags[] = ['id' => $value, 'text' => $course->name.'('.$courseType. ') - '. $course->coach->name];
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
                foreach($course->days as $day){
                    $displayData = $day->day_string . ' - ';
                    if(count($day->hours) > 1){
                        $displayData .= $day->hours[0]->hour_string;
                        array_push($data, $displayData);
                        $displayData = $day->day_string . ' - ';
                        $displayData .= $day->hours[1]->hour_string;
                        array_push($data, $displayData);
                    }
                    else{
                        $displayData .= $day->hours[0]->hour_string;
                        array_push($data, $displayData);
                    }
                }
            }
            else{
                if(count($course->days) > 0){
                    foreach($course->days as $day){
                        $displayData = $day->day_string . ' - ';
                        if(count($day->hours) > 1){
                            $displayData .= $day->hours[0]->hour_string;
                            array_push($data, $displayData);
                            $displayData = $day->day_string . ' - ';
                            $displayData .= $day->hours[1]->hour_string;
                            array_push($data, $displayData);
                        }
                        else{
                            $displayData .= $day->hours[0]->hour_string;
                            array_push($data, $displayData);
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
        $hari = array ( 1 =>    'Senin',
            'Selasa',
            'Rabu',
            'Kamis',
            'Jumat',
            'Sabtu',
            'Minggu'
        );
        $today = Carbon::now('Asia/Jakarta')->format('N');
        $dayDB = Day::select('course_id')->where('day_string', $hari[$today])->get();
        $courses = Course::whereIn('id', $dayDB)->get();

//        $now = Carbon::now('Asia/Jakarta');
//        $day = Carbon::now('Asia/Jakarta')->format('N');
//
//        //Convert Day from English to Indonesian
//        switch ($day){
//            case 'Monday': $dayQuery = 'Senin';
//                            break;
//            case 'Tuesday': $dayQuery = 'Selasa';
//                            break;
//            case 'Wednesday': $dayQuery = 'Rabu';
//                break;
//            case 'Thursday': $dayQuery = 'Kamis';
//                break;
//            case 'Friday': $dayQuery = 'Jumat';
//                break;
//            case 'Saturday': $dayQuery = 'Sabtu';
//                break;
//            case 'Sunday': $dayQuery = 'Minggu';
//                break;
//            default: $dayQuery = 'Senin';
//                break;
//        }
//        $courses = Course::where('type', 2)->where('day', 'LIKE', '%'. $dayQuery . '%')->get();
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
