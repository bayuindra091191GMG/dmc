<?php

namespace App\Http\Controllers\Admin;

use App\Models\Attendance;
use App\Models\Auth\User\User;
use App\Models\Coach;
use App\Models\Course;
use App\Models\CourseDetail;
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
    public function indexMuaythai()
    {
        $courseType = "muaythai";
        $type = 1;

        $data = [
            'courseType'        => $courseType,
            'type'              => $type
        ];

        return view('admin.courses.index')->with($data);
    }
    public function indexDance()
    {
        $courseType = "dance";
        $type = 2;

        $data = [
            'courseType'        => $courseType,
            'type'              => $type
        ];

        return view('admin.courses.index')->with($data);
    }
    public function indexGymnastic()
    {
        $courseType = "gymnastic";
        $type = 4;

        $data = [
            'courseType'        => $courseType,
            'type'              => $type
        ];

        return view('admin.courses.index')->with($data);
    }
    public function indexPrivate()
    {
        $courseType = "private";
        $type = 3;

        $data = [
            'courseType'        => $courseType,
            'type'              => $type
        ];

        return view('admin.courses.index')->with($data);
    }

    public function index()
    {
        return view('admin.courses.index', compact('selectedCourse'));
    }


    //DataTables

    /**
     * Process datatables ajax request.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function getIndex(Request $request)
    {
        $type = $request->input('type');

        $courses = Course::where('type', $type)
            ->orderBy('name')
            ->get();

        return DataTables::of($courses)
            ->setTransformer(new CourseTransformer)
            ->addIndexColumn()
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $coaches = Coach::where('id', '!=', 0)->get();

        $type = intval($request->type);
        if($type === 1){
            $backRoute = 'admin.muaythai.courses';
        }
        else if($type === 2){
            $backRoute = 'admin.dance.courses';
        }
        else if($type === 3){
            $backRoute = 'admin.private.courses';
        }
        else{
            $backRoute = 'admin.gymnastic.courses';
        }

        $data = [
            'type'      => $type,
            'coaches'   => $coaches,
            'backRoute' => $backRoute
        ];

        return view('admin.courses.create')->with($data);
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
        if($request->input('type') == 1){
            $meetingAmounts = $request->input('meeting_amount');
            $trainer = 0;
            $validAmount = $request->input('valid');
            $hour = "NONE";
        }
        else if($request->input('type') == 3){
            $meetingAmounts = 0;
            $trainer = $request->input('coach_id');
        }
        else{
            $trainer = $request->input('coach_id');
        }

        $price = str_replace('.','', $request->get('price'));

        $newCourse = Course::create([
            'name'              => $request->input('name'),
            'type'              => $request->input('type'),
            'price'             => $price,
            'coach_id'          => $trainer,
            'meeting_amount'    => $meetingAmounts,
            'valid'             => $validAmount,
            'status_id'         => 1
        ]);

        // Get studio input
        if($request->input('studio') != '-1'){
            $newCourse->studio = $request->input('studio');
            $newCourse->save();
        }

        //Save Day and Hour if type == 2 or 3
        if(($request->input('type') == 2 || $request->input('type') == 4 || $request->input('type') == 3)
            && !empty($days)){
            $days = $request->input('chk');
            foreach($days as $day){
                $newDay = Day::create([
                    'course_id'     => $newCourse->id,
                    'day_string'    => $day
                ]);

                $dayString = $day;

                if($dayString === 'Senin'){
                    $dayNumber = 1;
                }
                else if($dayString === 'Selasa'){
                    $dayNumber = 2;
                }
                else if($dayString === 'Rabu'){
                    $dayNumber = 3;
                }
                else if($dayString === 'Kamis'){
                    $dayNumber = 4;
                }
                else if($dayString === 'Jumat'){
                    $dayNumber = 5;
                }
                else if($dayString === 'Sabtu'){
                    $dayNumber = 6;
                }
                else{
                    $dayNumber = 7;
                }

                //Save Hour
                switch ($day){
                    case 'Senin':
                        Hour::create([
                            'day_id'        => $newDay->id,
                            'hour_string'   => $request->input('hourMonday1')
                        ]);

                        $hourString = $request->input('hourMonday1');
                        CourseDetail::create([
                            'course_id'         => $newCourse->id,
                            'day_number'        => $dayNumber,
                            'day_name'          => $dayString,
                            'time'              => $hourString,
                            'max_capacitiy'     => 0,
                            'current_capacity'  => 0,
                            'status_id'         => 1
                        ]);

                        if($request->input('hourMonday2') != null){
                            Hour::create([
                                'day_id'        => $newDay->id,
                                'hour_string'   => $request->input('hourMonday2')
                            ]);

                            $hourString = $request->input('hourMonday2');
                            CourseDetail::create([
                                'course_id'         => $newCourse->id,
                                'day_number'        => $dayNumber,
                                'day_name'          => $dayString,
                                'time'              => $hourString,
                                'max_capacitiy'     => 0,
                                'current_capacity'  => 0,
                                'status_id'         => 1
                            ]);
                        }
                        break;
                    case 'Selasa':
                        Hour::create([
                            'day_id'        => $newDay->id,
                            'hour_string'   => $request->input('hourTuesday1')
                        ]);

                        $hourString = $request->input('hourTuesday1');
                        CourseDetail::create([
                            'course_id'         => $newCourse->id,
                            'day_number'        => $dayNumber,
                            'day_name'          => $dayString,
                            'time'              => $hourString,
                            'max_capacitiy'     => 0,
                            'current_capacity'  => 0,
                            'status_id'         => 1
                        ]);

                        if($request->input('hourTuesday2') != null){
                            Hour::create([
                                'day_id'        => $newDay->id,
                                'hour_string'   => $request->input('hourTuesday2')
                            ]);

                            $hourString = $request->input('hourTuesday2');
                            CourseDetail::create([
                                'course_id'         => $newCourse->id,
                                'day_number'        => $dayNumber,
                                'day_name'          => $dayString,
                                'time'              => $hourString,
                                'max_capacitiy'     => 0,
                                'current_capacity'  => 0,
                                'status_id'         => 1
                            ]);
                        }
                        break;
                    case 'Rabu':
                        Hour::create([
                            'day_id'        => $newDay->id,
                            'hour_string'   => $request->input('hourWednesday1')
                        ]);

                        $hourString = $request->input('hourWednesday1');
                        CourseDetail::create([
                            'course_id'         => $newCourse->id,
                            'day_number'        => $dayNumber,
                            'day_name'          => $dayString,
                            'time'              => $hourString,
                            'max_capacitiy'     => 0,
                            'current_capacity'  => 0,
                            'status_id'         => 1
                        ]);

                        if($request->input('hourWednesday2') != null){
                            Hour::create([
                                'day_id'        => $newDay->id,
                                'hour_string'   => $request->input('hourWednesday2')
                            ]);

                            $hourString = $request->input('hourWednesday2');
                            CourseDetail::create([
                                'course_id'         => $newCourse->id,
                                'day_number'        => $dayNumber,
                                'day_name'          => $dayString,
                                'time'              => $hourString,
                                'max_capacitiy'     => 0,
                                'current_capacity'  => 0,
                                'status_id'         => 1
                            ]);
                        }
                        break;
                    case 'Kamis':
                        Hour::create([
                            'day_id'        => $newDay->id,
                            'hour_string'   => $request->input('hourThursday1')
                        ]);

                        $hourString = $request->input('hourThursday1');
                        CourseDetail::create([
                            'course_id'         => $newCourse->id,
                            'day_number'        => $dayNumber,
                            'day_name'          => $dayString,
                            'time'              => $hourString,
                            'max_capacitiy'     => 0,
                            'current_capacity'  => 0,
                            'status_id'         => 1
                        ]);

                        if($request->input('hourThursday2') != null){
                            Hour::create([
                                'day_id'        => $newDay->id,
                                'hour_string'   => $request->input('hourThursday2')
                            ]);

                            $hourString = $request->input('hourThursday2');
                            CourseDetail::create([
                                'course_id'         => $newCourse->id,
                                'day_number'        => $dayNumber,
                                'day_name'          => $dayString,
                                'time'              => $hourString,
                                'max_capacitiy'     => 0,
                                'current_capacity'  => 0,
                                'status_id'         => 1
                            ]);
                        }
                        break;
                    case 'Jumat':
                        Hour::create([
                            'day_id'        => $newDay->id,
                            'hour_string'   => $request->input('hourFriday1')
                        ]);

                        $hourString = $request->input('hourFriday1');
                        CourseDetail::create([
                            'course_id'         => $newCourse->id,
                            'day_number'        => $dayNumber,
                            'day_name'          => $dayString,
                            'time'              => $hourString,
                            'max_capacitiy'     => 0,
                            'current_capacity'  => 0,
                            'status_id'         => 1
                        ]);

                        if($request->input('hourFriday2') != null){
                            Hour::create([
                                'day_id'        => $newDay->id,
                                'hour_string'   => $request->input('hourFriday2')
                            ]);

                            $hourString = $request->input('hourFriday2');
                            CourseDetail::create([
                                'course_id'         => $newCourse->id,
                                'day_number'        => $dayNumber,
                                'day_name'          => $dayString,
                                'time'              => $hourString,
                                'max_capacitiy'     => 0,
                                'current_capacity'  => 0,
                                'status_id'         => 1
                            ]);
                        }
                        break;
                    case 'Sabtu':
                        Hour::create([
                            'day_id'        => $newDay->id,
                            'hour_string'   => $request->input('hourSaturday1')
                        ]);

                        $hourString = $request->input('hourSaturday1');
                        CourseDetail::create([
                            'course_id'         => $newCourse->id,
                            'day_number'        => $dayNumber,
                            'day_name'          => $dayString,
                            'time'              => $hourString,
                            'max_capacitiy'     => 0,
                            'current_capacity'  => 0,
                            'status_id'         => 1
                        ]);

                        if($request->input('hourSaturday2') != null){
                            Hour::create([
                                'day_id'        => $newDay->id,
                                'hour_string'   => $request->input('hourSaturday2')
                            ]);

                            $hourString = $request->input('hourSaturday2');
                            CourseDetail::create([
                                'course_id'         => $newCourse->id,
                                'day_number'        => $dayNumber,
                                'day_name'          => $dayString,
                                'time'              => $hourString,
                                'max_capacitiy'     => 0,
                                'current_capacity'  => 0,
                                'status_id'         => 1
                            ]);
                        }
                        break;
                    case 'Minggu':
                        Hour::create([
                            'day_id'        => $newDay->id,
                            'hour_string'   => $request->input('hourSunday1')
                        ]);

                        $hourString = $request->input('hourSunday1');
                        CourseDetail::create([
                            'course_id'         => $newCourse->id,
                            'day_number'        => $dayNumber,
                            'day_name'          => $dayString,
                            'time'              => $hourString,
                            'max_capacitiy'     => 0,
                            'current_capacity'  => 0,
                            'status_id'         => 1
                        ]);

                        if($request->input('hourSunday2') != null){
                            Hour::create([
                                'day_id'        => $newDay->id,
                                'hour_string'   => $request->input('hourSunday2')
                            ]);

                            $hourString = $request->input('hourSunday2');
                            CourseDetail::create([
                                'course_id'         => $newCourse->id,
                                'day_number'        => $dayNumber,
                                'day_name'          => $dayString,
                                'time'              => $hourString,
                                'max_capacitiy'     => 0,
                                'current_capacity'  => 0,
                                'status_id'         => 1
                            ]);
                        }
                        break;
                }
            }
        }

        Session::flash('message', 'Berhasil membuat data Kelas baru!');

        $type = $request->input('type');
        if($type == 1){
            return redirect()->route('admin.muaythai.courses');
        }
        else if($type == 2){
            return redirect()->route('admin.dance.courses');
        }
        else if($type == 3){
            return redirect()->route('admin.private.courses');
        }
        else{
            return redirect()->route('admin.gymnastic.courses');
        }
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

        $type = $course->type;
        if($type === 1){
            $backRoute = 'admin.muaythai.courses';
        }
        else if($type === 2){
            $backRoute = 'admin.dance.courses';
        }
        else if($type === 3){
            $backRoute = 'admin.private.courses';
        }
        else{
            $backRoute = 'admin.gymnastic.courses';
        }

        //Get customer/murid
        $customers = Schedule::where('course_id', $course->id)->get();
        return view('admin.courses.show', ['course' => $course, 'days' => $days, 'hours' => $hours, 'customers' => $customers, 'backRoute' => $backRoute]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Course $course
     * @return \Illuminate\Http\Response
     */
    public function edit(Course $course)
    {
        $coaches = Coach::orderBy('name')->get();
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
    public function update(Request $request)
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

        $course = Course::find($request->input('edited_id'));
        $course->name = $request->get('name');
        $course->type = $request->get('type');
        $course->price = $price;
        $course->coach_id = $request->get('coach_id');
        $course->meeting_amount = $request->get('meeting_amount');
        $course->updated_at = $dateTimeNow;
        $course->status_id = $request->get('status');

        // Get studio input
        if($request->input('studio') != '-1'){
            $course->studio = $request->input('studio');
        }
        else{
            $course->studio = null;
        }

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
            if($course->coach_id === 0){
                $coachName = 'Tidak Ada Coach';
            }
            else{
                $coachName = $course->coach->name;
            }

            $text = $course->name.'('.$courseType. ')';
            if(!empty($course->studio)){
                $text .= ' - Studio '. $course->studio;
            }

            $text.= ' - '. $coachName;

            $formatted_tags[] = ['id' => $course->id, 'text' => $text];
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
                $courseType = "Muaythai";
            }
            else if($course->type == 2){
                $courseType = "Dance";
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

        if(!empty($request->type)){
            $courses = Course::where('name', 'LIKE', '%'. $term. '%')
                ->where('type', $request->type)
                ->where('status_id', 1)
                ->get();
        }
        else{
            $courses = Course::where('name', 'LIKE', '%'. $term. '%')
                ->get();
        }

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

            if($course->coach_id === 0){
                $coachName = 'Tidak Ada Coach';
            }
            else{
                $coachName = $course->coach->name;
            }



            $value = $course->id. '#'. $course->name. '#'. $course->coach->name. '#'. $course->price. '#'. $course->meeting_amount;

            $text = $course->name.'('.$courseType. ')';
            if(!empty($course->studio)){
                $text .= ' - Studio '. $course->studio;
            }

            $text.= ' - '. $coachName;

            $formatted_tags[] = ['id' => $value, 'text' => $text];
        }

        return \Response::json($formatted_tags);
    }

    public function getExtendedCoursesDay(Request $request){
        $term = trim($request->q);
        $type = $request->type;
        if(!empty($request->type)){
            $couseDetails = CourseDetail::where('status_id', 1)
                ->whereHas('course', function ($query) use ($type, $term){
                    $query
                        ->where('name', 'LIKE', '%'. $term. '%')
                        ->where('type', $type)
                        ->where('status_id', 1)
                    ;
                })
                ->get();
        }
        else{
            $couseDetails = CourseDetail::where('status_id', 1)
                ->whereHas('course', function ($query) use ($type, $term){
                    $query
                        ->where('name', 'LIKE', '%'. $term. '%')
                        ->where('status_id', 1)
                    ;
                })
                ->get();
        }

        $formatted_tags = [];

        foreach ($couseDetails as $courseDetail) {
            if($courseDetail->course->type == 1){
                $courseType = "Muaythai";
            }
            else if($courseDetail->course->type == 2){
                $courseType = "Dance";
            }
            else if($courseDetail->course->type == 4){
                $courseType = "Gymnastic";
            }
            else{
                $courseType = "Private";
            }

            if($courseDetail->course->coach_id === 0){
                $coachName = 'Tidak Ada Coach';
            }
            else{
                $coachName = $courseDetail->course->coach->name;
            }

            $dateDetail = $courseDetail->day_name. " - ".$courseDetail->time;
            $value = $courseDetail->course->id. '#'. $courseDetail->course->name. '#'. $courseDetail->course->coach->name. '#'. $courseDetail->course->price. '#'. $courseDetail->course->meeting_amount. '#'. $dateDetail;

            $text = $courseDetail->course->name.'('.$courseType. ')';
            if(!empty($courseDetail->course->studio)){
                $text .= ' - Studio '. $courseDetail->course->studio;
            }

            $text.= ' ('. $dateDetail.')';
            $text.= ' - '. $coachName;

            $formatted_tags[] = ['id' => $value, 'text' => $text];

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
        $type = 0;
        return view('admin.courses.thisday', compact('type'));
    }

    public function thisDayMuaythaiCourses(){
        $type = 1;
        return view('admin.courses.thisday', compact('type'));
    }

    public function thisDayDanceCourses(){
        $type = 2;
        return view('admin.courses.thisday', compact('type'));
    }

    public function thisDayPrivateCourses(){
        $type = 3;
        return view('admin.courses.thisday', compact('type'));
    }

    public function thisDayGymnasticCourses(){
        $type = 4;
        return view('admin.courses.thisday', compact('type'));
    }

    public function getThisDayCourses(Request $request){
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

        $type = intval($request->input('type'));

        if($type !== 0){
            $courses = Course::whereIn('id', $dayDB)
                ->where('type', $type)
                ->get();
        }
        else{
            $courses = Course::whereIn('id', $dayDB)->get();
        }

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
