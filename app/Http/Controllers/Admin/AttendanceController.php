<?php

namespace App\Http\Controllers\Admin;

use App\Libs\Utilities;
use App\Models\Attendance;
use App\Models\Auth\User\User;
use App\Models\Coach;
use App\Models\Course;
use App\Models\Customer;
use App\Models\CustomerPointHistory;
use App\Models\NumberingSystem;
use App\Models\Schedule;
use App\Models\TransactionDetail;
use App\Models\TransactionHeader;
use App\Transformer\MasterData\AttendanceTransformer;
use App\Transformer\MasterData\CoachTransformer;
use App\Transformer\MasterData\TransactionHeaderTranformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexMuaythai()
    {
        $selectedCourse = "muaythai";
//        dd($selectedCourse);
        return view('admin.attendances.index', compact('selectedCourse'));
    }

    public function index()
    {
        return view('admin.attendances.index');
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function anyData(Request $request)
    {
        $selectedCourseString = $request->input('course');
        $selectedCourse = 1;
        if($selectedCourseString == 'muaythai'){
            $selectedCourse = 1;
        }
//        error_log($selectedCourse);
//        $courseIds = Course::select('id')->where('type', $selectedCourse)->get();
//        $scheduleIds = Schedule::select('id')->whereIn('course_id', $courseIds)->get();
//        $attendances = Attendance::whereIn('schedule_id', $scheduleIds)->dateDescending()->get();

        $type = $selectedCourse;
        $attendances = Attendance::whereHas('schedule', function($query1) use ($type){
                $query1->whereHas('course', function($query2) use ($type){
                    $query2->where('type', $type);
                });
            })
            ->orderBy('date', 'desc')
            ->get();

        return DataTables::of($attendances)
            ->setTransformer(new AttendanceTransformer())
            ->addIndexColumn()
            ->make(true);
    }

    public function show(TransactionHeader $transaction){
        $header = $transaction;

        $data = [
            'header'            => $header
        ];

        return View('admin.attendances.show')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customer = null;
        $customerPlaceholder = null;
        $schedules = null;
        if(!empty(request()->customer)){
            $customer = Customer::find(request()->customer);

            //get Ongoing schedule (status = 3) and attendance only muaythai
            $schedules = Schedule::where('customer_id', $customer->id)
                ->where('status_id', 3)
                ->whereHas('course', function($query){
                    $query->where('type', 1);
                })
                ->get();
            $customerPlaceholder = $customer->name. ' - '. $customer->email. ' - '. $customer->phone;
        }

        $data = [
            'customer'              => $customer,
            'customerPlaceholder'   => $customerPlaceholder,
            'schedules'             => $schedules
        ];

        return view('admin.attendances.create')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        try{
            $validator = Validator::make($request->all(),[
                'customer_id'        => 'required',
                'schedule_id'        => 'required'
            ]);

            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
            }
            $customerID = $request->input('customer_id');
            $scheduleID = $request->input('schedule_id');

            //Check payment due or not
            $now = Carbon::now('Asia/Jakarta');
            //Check if already absence today
            $attendanceExist = Attendance::where('customer_id', $customerID)
                ->where('schedule_id', $scheduleID)
                ->whereDay('date', $now->day)
                ->whereMonth('date', $now->month)
                ->exists();
            if($attendanceExist){
                return redirect()
                    ->back()
                    ->withErrors('Sudah Melakukan Absensi di Hari ini', 'default')
                    ->withInput();
            }

            $scheduleDB = Schedule::find($scheduleID);
            //check if schedule is today
            $hari = array ( 1 =>    'Senin',
                'Selasa',
                'Rabu',
                'Kamis',
                'Jumat',
                'Sabtu',
                'Minggu'
            );

            if($scheduleDB->course->type != 4 && $scheduleDB->day != "Bebas"){
                $today = Carbon::now('Asia/Jakarta')->format('N');
                if(strpos($scheduleDB->day, $hari[$today]) === false){
                    return redirect()
                        ->back()
                        ->withErrors('Tidak dapat Melakukan Absensi di Hari ini', 'default')
                        ->withInput();
                }
            }


            //Check if schedule date still
            $nowFormated = Carbon::parse(date_format($now,'Y-m-d'));
            $scheduleFinishDate = Carbon::parse(date_format($scheduleDB->finish_date, 'Y-m-d'));
            if($scheduleFinishDate < $nowFormated){
                $scheduleDB->status_id = 4;
                $scheduleDB->save();

                return redirect()
                    ->back()
                    ->withErrors('Masa Berlaku Kelas sudah Habis.', 'default')
                    ->withInput();
            }

            //check user already payment or not
            if($scheduleDB->course->type == 1){
                if($scheduleDB->status_id == 2){
                    return redirect()
                        ->back()
                        ->withErrors('Harap Melakukan Pembayaran Pada Kelas ini.', 'default')
                        ->withInput();
                }
            }

            $attendancePending = Attendance::where('customer_id', $customerID)
                ->where('schedule_id', $scheduleID)
                ->where('status_id', 2)
                ->orderBy('created_at')
                ->first();
            if($scheduleDB->course->type == 4){
                $startWeek = Carbon::now()->startOfWeek()->toDateTimeString();
                $endWeek = Carbon::now()->endOfWeek()->toDateTimeString();

                $countAttendanceOfWeek = Attendance::where('customer_id', $customerID)
                    ->where('schedule_id', $scheduleID)
                    ->where('status_id', 1)
                    ->whereBetween('date', [$startWeek, $endWeek])
                    ->count();

                //for gymnastic checking only 2 times per week
                if($countAttendanceOfWeek >= 2){
                    return redirect()
                        ->back()
                        ->withErrors('Absensi pada Kelas Gymnastic maksimal 2 kali per minggu.', 'default')
                        ->withInput();
                }

                $today = Carbon::now('Asia/Jakarta')->format('N');

                //for gymnastic checking for today attendance
                if($attendancePending == null){
                    if(strpos($scheduleDB->day, $hari[$today])===false){
                        return redirect()
                            ->back()
                            ->withErrors('Tidak dapat Melakukan Absensi di Hari ini', 'default')
                            ->withInput();
                    }
                }
            }

            //change schedule meeting amount
            if($scheduleDB->day == "Bebas"){
                if($scheduleDB->meeting_amount == 0){
                    return redirect()
                        ->back()
                        ->withErrors('Pertemuan sudah habis', 'default')
                        ->withInput();
                }

                //change schedule amount (package increase, class decrese)
                $temp = $scheduleDB->meeting_amount;
                $scheduleDB->meeting_amount = $temp-1;
                $scheduleDB->save();
            }
            else{
                if($scheduleDB->course->type == 3){
                    //change schedule amount (package increase, class decrese)
                    $temp = $scheduleDB->meeting_amount;
                    $scheduleDB->meeting_amount = $temp-1;
                    $scheduleDB->save();
                }
                if($scheduleDB->meeting_amount == $scheduleDB->course->meeting_amount){
                    return redirect()
                        ->back()
                        ->withErrors('Pertemuan sudah habis', 'default')
                        ->withInput();
                }

                //change schedule amount (package increase, class decrese)
                $temp = $scheduleDB->meeting_amount;
                $scheduleDB->meeting_amount = $temp+1;
                $scheduleDB->save();

            }

            if($attendancePending == null){
                //save the attendance
                $attendanceCount = Attendance::where('customer_id', $customerID)->where('schedule_id', $scheduleID)->count();
                $user = Auth::user();
                $attendanceCount++;
                $attendance = Attendance::create([
                    'customer_id'           => $customerID,
                    'schedule_id'           => $scheduleID,
                    'date'                  => $now->toDateTimeString(),
                    'meeting_number'        => $attendanceCount,
                    'status_id'             => 1,
                    'created_by'            => $user->id,
                    'created_at'            => $now->toDateTimeString()
                ]);
                $attendance->save();

                //Tambah Point jika MuayThai
                if($scheduleDB->course->type == 1){
                    $customerData = Customer::find($customerID);

                    $history = CustomerPointHistory::create([
                        'customer_id'   => $customerID,
                        'point_from'    => $customerData->point
                    ]);

                    $customerData->point += 10;
                    $customerData->save();

                    $history->point_add = 10;
                    $history->point_result = $customerData->point;
                    $history->attendance_id = $attendance->id;
                    $history->notes = 'Point bertambah dikarenakan absensi Muay Thai!';
                    $history->save();
                }
            }
            else{
                $attendanceCount = Attendance::where('customer_id', $customerID)->where('schedule_id', $scheduleID)->count();

                $attendancePending->date = $now->toDateTimeString();
                $attendancePending->status_id = 1;
                $attendancePending->save();
            }

            //check if user meeting done
            if($scheduleDB->day == "Bebas"){
                if($scheduleDB->meeting_amount == 0){
                    $scheduleDB->status_id = 4;
                    $scheduleDB->save();
                }
            }
            else{
                if($scheduleDB->course->type == 3){
                    if($scheduleDB->meeting_amount == 0){
                        $scheduleDB->status_id = 4;
                        $scheduleDB->save();
                    }
                }

                if($scheduleDB->meeting_amount == $scheduleDB->course->meeting_amount){
                    $scheduleDB->status_id = 4;
                    $scheduleDB->save();
                }
            }

            if($scheduleDB->course->type == 1 || $scheduleDB->course->type == 4){
                //Print Absen
                $customerData = Customer::find($customerID);
                $date = $now->toDateTimeString();
                $remainAttendance = $scheduleDB->course->meeting_amount - $attendanceCount;
                return view('admin.attendances.paper',
                    compact('scheduleDB', 'customerData', 'date', 'attendanceCount', 'remainAttendance'));
            }

            Session::flash('message', 'Berhasil membuat absensi!');

            return redirect()->route('admin.attendances');
        }
        catch (\Exception $exception){
            Log::error("AttendanceController - store Error: ". $exception->getMessage());
            return redirect()->route('admin.attendances');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
//    public function show(User $user)
//    {
//        return view('admin.users.show', ['user' => $user]);
//    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param TransactionHeader $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(TransactionHeader $transaction)
    {
        $header = $transaction;
        $date = Carbon::parse($transaction->date)->format('d M Y');

        $data = [
            'header'    => $header,
            'date'      => $date
        ];

        return view('admin.attendances.edit')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Coach $coach
     * @return mixed
     */
    public function update(Request $request, TransactionHeader $transaction)
    {
        $validator = Validator::make($request->all(),[
            'date'        => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::user();
        $now = Carbon::now('Asia/Jakarta');

        $trxHeader = $transaction;
        $date = Carbon::createFromFormat('d M Y', $request->input('date'), 'Asia/Jakarta');
        $trxHeader->date = $date->toDateTimeString();
        $trxHeader->updated_by = $user->id;
        $trxHeader->updated_at = $now->toDateTimeString();

        $trxHeader->save();

        Session::flash('message', 'Berhasil mengubah absensi!');

        return redirect()->route('admin.attendances.show', ['transaction' => $trxHeader]);
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

            Session::flash('message', 'Berhasil menghapus data absensi '. $coach->name);
            return Response::json(array('success' => 'VALID'));
        }
        catch(\Exception $ex){
            return Response::json(array('errors' => 'INVALID'));
        }
    }

    public function report(){
        return view('admin.attendances.report');
    }

    public function showDocument(Request $request){
        $course = Course::find($request->input('course'));

        if($course->type == 1){
            $days[0] = "Bebas";
            $hours[0] = "Bebas";
        }
        else {
            $days = preg_split('@;@', $course->day, NULL, PREG_SPLIT_NO_EMPTY);
            $hours = preg_split('@;@', $course->hour, NULL, PREG_SPLIT_NO_EMPTY);
        }

        //Get customer/murid
        $customers = Schedule::where('course_id', $course->id)->get();

        //Get Attendance
        $startDate = Carbon::parse($request->input('date'))->format('m');
        $finishDate = Carbon::parse($request->input('date'))->addMonth()->format('m');
        $attendanceData = [];
        foreach ($customers as $customer){
            if(Attendance::
                where([
                    ['customer_id', $customer->customer_id],
                    ['schedule_id', $customer->id]
                ])
                    ->whereMonth('created_at', '>=', $startDate)
                    ->whereMonth('created_at', '<', $finishDate)
                    ->orderBy('created_at')
                    ->exists())
            {
                $attendance = Attendance::
                where([
                    ['customer_id', $customer->customer_id],
                    ['schedule_id', $customer->id]
                ])
                    ->whereMonth('created_at', '>=', $startDate)
                    ->whereMonth('created_at', '<', $finishDate)
                    ->orderBy('created_at')
                    ->get();
                array_push($attendanceData, $attendance);
            }
        }
        $chosenDate = Carbon::parse($request->input('date'))->format('M Y');

        return view('admin.attendances.show-report', ['course' => $course, 'days' => $days, 'hours' => $hours, 'customers' => $customers, 'attendanceData' => $attendanceData, 'chosenDate' => $chosenDate]);
    }

    public function printAttendancePaper(){
        $attendanceData = Attendance::find(request()->attendanceId);
        $scheduleDB = Schedule::find(request()->scheduleId);
        $customerData = Customer::find(request()->customerId);
        $attendanceCount = Attendance::where('customer_id', $customerData->id)->where('schedule_id', $scheduleDB->id)->count();
        $date = $attendanceData->date->toDateTimeString();
        return view('admin.attendances.paper', compact('scheduleDB', 'customerData', 'date', 'attendanceCount'));
    }

    public function scanReceiver(){
        return view('admin.attendances.scan');
    }
}
