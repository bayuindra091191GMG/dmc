<?php

namespace App\Http\Controllers\Admin;

use App\Models\Auth\User\User;
use App\Models\Coach;
use App\Models\Course;
use App\Models\Customer;
use App\Models\TransactionDetail;
use App\Models\TransactionHeader;
use App\Transformer\MasterData\CoachTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('admin.transactions.index');
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
        $coaches = Coach::all();
        return DataTables::of($coaches)
            ->setTransformer(new CoachTransformer())
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
        return view('admin.transactions.create', compact('customers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        $validator = Validator::make($request->all(), [
//            'name'              => 'required|max:50',
//            'email'             => 'email'
//        ]);
//
//        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors())->withInput($request->all());


        // Validate details
        $courses = $request->input('course_id');
        $days = $request->input('day');
        $startDates = $request->input('start_date');
        $finishDates = $request->input('finish_date');
        $detailPrices = $request->input('total_price');
        $detailDiscount = $request->input('discount');
        $valid = true;
        $tmpTotal = 0;
        $tmpDiscount = 0;
        $idx = 0;

        foreach($courses as $item){
            if(empty($item)) $valid = false;
            $tmpTotal = $detailPrices[$idx];
            $tmpDiscount = $detailDiscount[$idx];

            $idx++;
        }

        if($tmpTotal < $tmpDiscount){
            return redirect()->back()->withErrors('Diskon Tidak boleh lebih kecil daripada Biaya!', 'default')->withInput($request->all());
        }

        if(!$valid){
            return redirect()->back()->withErrors('Kelas Wajib dipilih!', 'default')->withInput($request->all());
        }

        $customer = $request->get('customer_id');
        if($customer == null){
            return redirect()->back()->withErrors("Pilih Murid terlebih dahulu!")->withInput($request->all());
        }

        //Get All Data and store
        $now = Carbon::now('Asia/Jakarta');
        $header = TransactionHeader::create([
            'customer_id'   => $request->get('customer_id'),
            'date'          => $now->toDateString(),
            'total_payment' => $request->get('total_price'),
            'status_id'     => 3
        ]);

        //Details
        $i = 0;
        foreach ($courses as $course){
            $courseData = Course::find($course);

            $tempStart = strtotime($startDates[$i]);
            $start = date('Y-m-d', $tempStart);
            $tempFinish = strtotime($finishDates[$i]);
            $finish = date('Y-m-d', $tempFinish);

            $subTotal = $detailPrices[$i] - $detailDiscount[$i];

            TransactionDetail::create([
                'header_id'         => $header->id,
                'class_id'          => $courseData->id,
                'day'               => $days[$i],
                'meeting_amounts'   => $courseData->meeting_amounts,
                'meeting_attendeds' => 0,
                'class_start_date'  => $start,
                'class_end_date'    => $finish,
                'price'             => $detailPrices[$i],
                'discount'          => $detailDiscount[$i],
                'subtotal'          => $subTotal
            ]);
            $i++;
        }

        Session::flash('message', 'Berhasil membuat data Jadwal baru!');

        return redirect()->route('admin.transactions');
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
