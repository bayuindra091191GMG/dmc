<?php

namespace App\Http\Controllers\Admin;

use App\Models\Coach;
use App\Models\Course;
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
        $startDates = $request->input('start_date');
        $finishDates = $request->input('finish_date');
        $valid = true;
        $validClass = true;
        $validDate = true;
        $idx = 0;

        foreach($courses as $item){
            if(empty($item)) $valid = false;

            //Check if the customer already take that class
            $tmpCourse = Schedule::where('customer_id', $customer)->where('course_id', $item)->first();
            if($tmpCourse != null){
                $validClass = false;
            }

            $tempStart = strtotime($startDates[$idx]);
            $tempFinish = strtotime($finishDates[$idx]);

            $year1 = date('Y', $tempStart);
            $year2 = date('Y', $tempFinish);

            $month1 = date('m', $tempStart);
            $month2 = date('m', $tempFinish);

            $diff = (($year2 - $year1) * 12) + ($month2 - $month1);

            if($diff <= 0){
                $validDate = false;
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

        if(!$validDate){
            return redirect()->back()->withErrors("Tanggal yang dimasukan salah!");
        }

        //Get All Data and store
        $i = 0;
        foreach ($courses as $course){
            $courseData = Course::find($course);

            $tempStart = strtotime($startDates[$i]);
            $start = date('Y-m-d', $tempStart);
            $tempFinish = strtotime($finishDates[$i]);
            $finish = date('Y-m-d', $tempFinish);

            $year1 = date('Y', $tempStart);
            $year2 = date('Y', $tempFinish);

            $month1 = date('m', $tempStart);
            $month2 = date('m', $tempFinish);

            $diff = (($year2 - $year1) * 12) + ($month2 - $month1);

            Schedule::create([
                'customer_id'       => $request->get('customer_id'),
                'course_id'         => $course,
                'start_date'        => $start,
                'finish_date'       => $finish,
                'meeting_amount'    => $courseData->meeting_amount,
                'month_amount'      => $diff,
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
    public function edit(Coach $coach)
    {
        return view('admin.coaches.edit', ['coach' => $coach]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Coach $coach
     * @return mixed
     */
    public function update(Request $request, Coach $coach)
    {
        $validator = Validator::make($request->all(), [
            'name'              => 'required|max:50',
            'email'             => 'email'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());

        $dateTimeNow = Carbon::now('Asia/Jakarta');

        $coach->name = $request->get('name');
        $coach->email = $request->get('email');
        $coach->address = $request->get('address');
        $coach->phone = $request->get('phone');
        $coach->updated_at = $dateTimeNow;
        $coach->save();

        Session::flash('message', 'Berhasil mengubah data Trainer!');

        return redirect()->route('admin.coaches.edit', ['coach' => $coach]);
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
}
