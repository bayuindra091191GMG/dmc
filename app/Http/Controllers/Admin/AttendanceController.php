<?php

namespace App\Http\Controllers\Admin;

use App\Libs\Utilities;
use App\Models\Attendance;
use App\Models\Auth\User\User;
use App\Models\Coach;
use App\Models\Course;
use App\Models\Customer;
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
use Yajra\DataTables\DataTables;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('admin.attendances.index');
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function anyData()
    {
        $attendances = Attendance::dateDescending()->get();
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
            $schedules = Schedule::where('customer_id', $customer->id)
                ->where('status_id', 3)
                ->get();
            $customerPlaceholder = $customer->name. ' - '. $customer->email. ' - '. $customer->phone;
        }

        $data = [
            'customer'          => $customer,
            'customerPlaceholder'          => $customerPlaceholder,
            'schedules'          => $schedules
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

        //check attendance and schedule meeting amount
        $scheduleDB = Schedule::find($scheduleID);
        $attendanceCount = Attendance::where('customer_id', $customerID)->where('schedule_id', $scheduleID)->count();

        if($scheduleDB->meeting_amount == $attendanceCount){
            return redirect()
                ->back()
                ->withErrors('Pertemuan sudah habis', 'default')
                ->withInput();
        }

        //save the attendance
        $user = Auth::user();
        $now = Carbon::now('Asia/Jakarta');
        $attendanceCount++;
        $attendance = Attendance::create([
            'customer_id'           => $customerID,
            'schedule_id'           => $scheduleID,
            'date'                  => $now->toDateTimeString(),
            'meeting_number'        => $attendanceCount,
            'created_by'            => $user->id,
            'created_at'            => $now->toDateTimeString()
        ]);
        $attendance->save();

        //change schedule status if attendance is done
        if($scheduleDB->meeting_amount == $attendanceCount){
            $scheduleDB->status_id = 4;
            $scheduleDB->save();
        }

        Session::flash('message', 'Berhasil membuat absensi!');

        return redirect()->route('admin.attendances');
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
}