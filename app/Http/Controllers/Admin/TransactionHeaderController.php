<?php

namespace App\Http\Controllers\Admin;

use App\Libs\Utilities;
use App\Models\Auth\User\User;
use App\Models\Coach;
use App\Models\Course;
use App\Models\CourseDetail;
use App\Models\Customer;
use App\Models\CustomerVoucher;
use App\Models\NumberingSystem;
use App\Models\Schedule;
use App\Models\TransactionDetail;
use App\Models\TransactionHeader;
use App\Models\Voucher;
use App\Transformer\MasterData\CoachTransformer;
use App\Transformer\MasterData\TransactionHeaderTranformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use PDF;
use PDF2;

class TransactionHeaderController extends Controller
{
    /**
     * Display a listing of transaction.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('admin.transactions.index');
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function anyData()
    {
        $headers = TransactionHeader::all();
        return DataTables::of($headers)
            ->setTransformer(new TransactionHeaderTranformer)
            ->addIndexColumn()
            ->make(true);
    }

    public function show(TransactionHeader $transaction){
        $header = $transaction;

        $data = [
            'header'            => $header
        ];

        return View('admin.transactions.show')->with($data);
    }

    /**
     * Show the form for creating a new normal transaction.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customer = null;
        $vouchers = null;
        if(!empty(request()->customer)){
            $customer = Customer::find(request()->customer);
            if(DB::table('customer_vouchers')->where('customer_id', $customer->id)->exists()){
                $vouchers = CustomerVoucher::where('customer_id', $customer->id)->where('status_id', 1)->get();
            }
        }

        // Numbering System
        $sysNo = NumberingSystem::find(2);
        $autoNumber = Utilities::GenerateNumberPurchaseOrder($sysNo->document, $sysNo->next_no);

        $today = Carbon::today('Asia/Jakarta')->format('d M y');

        $data = [
            'customer'          => $customer,
            'autoNumber'        => $autoNumber,
            'today'             => $today,
            'vouchers'          => $vouchers
        ];

        return view('admin.transactions.create')->with($data);
    }

    /**
     * Store a newly created normal transaction in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'code'        => 'required|max:45|regex:/^\S*$/u',
            'date'        => 'required',
        ],[
            'code.regex'  => 'Nomor Transaksi harus tanpa spasi!'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Validate transaction number
        if(!$request->filled('auto_number') && (!$request->filled('code') || $request->input('retur_code') == "")){
            return redirect()->back()->withErrors('Nomor transaksi wajib diisi!', 'default')->withInput($request->all());
        }

        // Validate payment method
        if($request->input('payment_method') === '-1'){
            return redirect()->back()->withErrors('Pilih metode pembayaran!', 'default')->withInput($request->all());
        }

        // Validate details
        $schedules = $request->input('schedule');
        $prices = $request->input('price');
        $discounts = $request->input('discount');
        $valid = true;
        $i = 0;

//        if($schedules == null || $schedules->count() == 0){
        if(empty($schedules) || count($schedules) == 0){
            return redirect()->back()->withErrors('Detail kelas wajib diisi!', 'default')->withInput($request->all());
        }

        foreach($schedules as $schedule){
            if(empty($schedule)) $valid = false;
            if(empty($prices[$i]) || $prices[$i] == '0') $valid = false;

            // Validate schedule
            $scheduleObj = Schedule::find($schedule);
            if(empty($scheduleObj)){
                return redirect()->back()->withErrors('Jadwal atau kelas tidak ditemukan!', 'default')->withInput($request->all());
            }

            if($scheduleObj->status_id === 3){
                return redirect()->back()->withErrors('Jadwal atau kelas sudah melakukan transaksi!', 'default')->withInput($request->all());
            }

            // Validate discount
            $priceVad = str_replace('.','', $prices[$i]);
            $discountVad = str_replace('.','', $discounts[$i]);
            if((double) $discountVad > (double) $priceVad) return redirect()->back()->withErrors('Diskon tidak boleh melebihi harga!', 'default')->withInput($request->all());

            $i++;
        }

        if(!$valid){
            return redirect()->back()->withErrors('Detail harga wajib diisi!', 'default')->withInput($request->all());
        }

        // Check duplicate inventory
        $valid = Utilities::arrayIsUnique($schedules);
        if(!$valid){
            return redirect()->back()->withErrors('Detail transaksi tidak boleh kembar!', 'default')->withInput($request->all());
        }

        // Generate auto number
        $trxCode = 'default';
        if($request->filled('auto_number')){
            $sysNo = NumberingSystem::find(2);
            $trxCode = Utilities::GenerateNumber($sysNo->document, $sysNo->next_no);

            // Check existing number
            if(TransactionHeader::where('code', $trxCode)->exists()){
                return redirect()->back()->withErrors('Nomor Transaksi sudah terdaftar!', 'default')->withInput($request->all());
            }

            $sysNo->next_no++;
            $sysNo->save();
        }
        else{
            $trxCode = $request->input('code');

            // Check existing number
            if(TransactionHeader::where('code', $trxCode)->exists()){
                return redirect()->back()->withErrors('Nomor Transaksi sudah terdaftar!', 'default')->withInput($request->all());
            }
        }

        // Generate invoice number
        $sysNoInvoice = NumberingSystem::find(1);
        $invNumber = Utilities::GenerateNumber($sysNoInvoice->document, $sysNoInvoice->next_no);
        $sysNoInvoice->next_no++;
        $sysNoInvoice->save();

        $user = Auth::user();
        $now = Carbon::now('Asia/Jakarta');

        $date = Carbon::createFromFormat('d M Y', $request->input('date'), 'Asia/Jakarta');

        $trxHeader = TransactionHeader::create([
            'code'                  => $trxCode,
            'type'                  => 1,
            'customer_id'           => $request->input('customer_id'),
            'date'                  => $date->toDateTimeString(),
            'payment_method'        => $request->input('payment_method'),
            'invoice_number'        => $invNumber,
            'status_id'             => 1,
            'created_by'            => $user->id,
            'created_at'            => $now->toDateTimeString(),
            'updated_by'            => $user->id,
            'updated_at'            => $now->toDateTimeString()
        ]);

        // Create transaction detail
        $totalPrice = 0;
        $totalDiscount = 0;
        $totalPayment = 0;
        $idx = 0;

        $months = $request->input('months');

        foreach($schedules as $schedule){
            if(!empty($schedule)){
                $month = floatval($months[$idx]);
                $priceStr = str_replace('.','', $prices[$idx]);
                $price = (double) $priceStr;
                $scheduleObj = Schedule::find($schedule);
                $trxDetail = TransactionDetail::create([
                    'header_id'             => $trxHeader->id,
                    'schedule_id'           => $schedule,
                    'day'                   => $scheduleObj->day,
                    'month_amount'          => $month,
                    'meeting_attendeds'     => 0,
                    'price'                 => $priceStr
                ]);

                // Check discount
//                if(!empty($discounts[$idx]) && $discounts[$idx] !== '0'){
//                    $discountStr = str_replace('.','', $discounts[$idx]);
//                    $trxDetail->discount = $discountStr;
//
//                    $discount = (double) $discountStr;
//                    $trxDetail->subtotal = $price - $discount;
//
//                    // Accumulate total price
//                    $totalPrice += $price;
//
//                    // Accumulate total discount
//                    $totalDiscount += $discount;
//                }
//                else{
//                    $trxDetail->subtotal = $price;
//                    $totalPrice += $price;
//                }

                $totalPrice += $month * $price;
                $trxDetail->subtotal = $month * $price;
                $trxDetail->meeting_amount = $scheduleObj->course->meeting_amount;

                // Accumulate subtotal
                $totalPayment += $trxDetail->subtotal;

                // Check more than 1 month payment
                if($month > 1){
                    if($scheduleObj->course->type === 1){
                        $addedTimes = $month - 1;

                        $addedValid = $addedTimes * $scheduleObj->course->valid;
                        $newFinishDate = Carbon::parse($scheduleObj->finish_date)->addDays($addedValid);
                        $scheduleObj->finish_date =  $newFinishDate->toDateTimeString();
                        $scheduleObj->meeting_amount +=  ($addedTimes * $scheduleObj->course->meeting_amount);

                        $trxDetail->meeting_amount += ($addedTimes * $scheduleObj->course->meeting_amount);
                    }
                    else{
                        $addedMonth = $month - 1;
                        $newFinishDate = Carbon::parse($scheduleObj->finish_date)->addMonthNoOverflow($addedMonth);
                        $scheduleObj->finish_date =  $newFinishDate->toDateTimeString();

                        $trxDetail->meeting_amount += ($addedMonth * $scheduleObj->course->meeting_amount);
                    }
                }

                if($request->filled('voucher_code') && $request->input('is_discount') == 1) {
                    if (DB::table('vouchers')->where('name', $request->input('voucher_code'))->exists()) {
                        $voucher = Voucher::where('name', $request->input('voucher_code'))->first();
                        if ($voucher->type == 'free_package') {
                            $trxDetail->meeting_amount += $voucher->free_package;
                        }

                        $customerVoucher = CustomerVoucher::where('voucher_id', $voucher->id)->where('customer_id', $request->input('customer_id'))->first();
                        $customerVoucher->status_id = 8;
                        $customerVoucher->save();
                    }
                }

                $trxDetail->save();

                // Activate schedule
                $scheduleObj->status_id = 3;
                $scheduleObj->save();

                // Increase student count
                if(!empty($schedule->day) && $scheduleObj->day != 'Bebas'){
                    $splitted = explode('-', $scheduleObj->day);
                    $dayString = trim($splitted[0]);
                    $timeString = trim($splitted[1]);

                    $courseDetail = CourseDetail::where('course_id', $scheduleObj->course_id)
                        ->where('day_name', $dayString)
                        ->where('time', $timeString)
                        ->first();
                    $courseDetail->current_capacity += 1;
                    $courseDetail->save();
                }

            }
            $idx++;
        }

        if($totalDiscount > 0) $trxHeader->total_discount = $totalDiscount;

        if($request->filled('registration_fee')){
            $fee = str_replace('.','', $request->input('registration_fee'));
        }
        else{
            $fee = 0;
        }

        $totalAmount = 0;
        $totalDiscount = 0;
        if($request->filled('voucher_code') && $request->input('is_discount') == 1){
            if(DB::table('vouchers')->where('name', $request->input('voucher_code'))->exists()) {
                $voucher = Voucher::where('name', $request->input('voucher_code'))->first();
                if ($voucher->type == 'discount_percentage') {
                    $totalDiscount = $totalPrice * $voucher->discount_percentage / 100;
                    $totalAmount = $totalPrice - $totalDiscount;
                } else if ($voucher->type == 'discount_total') {
                    $totalDiscount = $voucher->discount_total;
                    $totalAmount = $totalPrice - $voucher->discount_total;
                }

                $customerVoucher = CustomerVoucher::where('voucher_id', $voucher->id)->where('customer_id', $request->input('customer_id'))->first();
                $customerVoucher->status_id = 8;
                $customerVoucher->save();
            }
        }

        $totalPayment += $fee;
        $trxHeader->total_discount = $totalDiscount;
        $trxHeader->total_price = $totalAmount;
        $trxHeader->total_payment = $totalPayment;
        $trxHeader->registration_fee = $fee;
        $trxHeader->save();

        Session::flash('message', 'Berhasil membuat transaksi!');

        return redirect()->route('admin.transactions.show', ['transaction' => $trxHeader]);
    }

    public function storeComplete(Request $request){
        $validator = Validator::make($request->all(),[
            'code'        => 'required|max:45|regex:/^\S*$/u',
            'date'        => 'required',
        ],[
            'code.regex'  => 'Nomor Transaksi harus tanpa spasi!'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Validate transaction number
        if(!$request->filled('auto_number') && (!$request->filled('code') || $request->input('retur_code') == "")){
            return redirect()->back()->withErrors('Nomor transaksi wajib diisi!', 'default')->withInput($request->all());
        }

        // Validate student
        if(!empty($request->input('is_new_student'))){
            if(empty($request->input('student_name')) || empty($request->input('student_email'))){
                return redirect()->back()->withErrors('Nama dan alamat email murid wajib diisi!', 'default')->withInput($request->all());
            }
            else{
                // Validate unique student name
                $nameFound = Customer::where('name', 'LIKE', '%'. $request->input('student_name'). '%')->first();
                if(!empty($nameFound)){
                    return redirect()->back()->withErrors('Nama murid sudah terdaftar!', 'default')->withInput($request->all());
                }

                // Validate unique student email
                $emailFound = Customer::where('email', $request->input('student_email'))->first();
                if(!empty($emailFound)){
                    return redirect()->back()->withErrors('Alamat email sudah terdaftar!', 'default')->withInput($request->all());
                }
            }
        }
        else{
            if(empty($request->input('customer'))){
                return redirect()->back()->withErrors('Pilih murid!', 'default')->withInput($request->all());
            }
        }

        // Validate payment method
        if($request->input('payment_method') === '-1'){
            return redirect()->back()->withErrors('Pilih metode pembayaran!', 'default')->withInput($request->all());
        }

        // Validate details
        $courses = $request->input('course');
        $prices = $request->input('price');
        $days = $request->input('day');
//        $discounts = $request->input('discount');
        $valid = true;
        $i = 0;

//        if($schedules == null || $schedules->count() == 0){
        if(empty($courses) || count($courses) == 0){
            return redirect()->back()->withErrors('Detil kelas wajib diisi!', 'default')->withInput($request->all());
        }

        foreach($courses as $course){
            if(empty($course)) $valid = false;
            if(empty($prices[$i]) || $prices[$i] == '0') $valid = false;

            // Validate schedule
//            $scheduleObj = Schedule::find($schedule);
//            if(empty($scheduleObj)){
//                return redirect()->back()->withErrors('Jadwal atau kelas tidak ditemukan!', 'default')->withInput($request->all());
//            }
//
//            if($scheduleObj->status_id === 3){
//                return redirect()->back()->withErrors('Jadwal atau kelas sudah melakukan transaksi!', 'default')->withInput($request->all());
//            }

            // Validate discount
//            $priceVad = str_replace('.','', $prices[$i]);
//            $discountVad = str_replace('.','', $discounts[$i]);
//            if((double) $discountVad > (double) $priceVad) return redirect()->back()->withErrors('Diskon tidak boleh melebihi harga!', 'default')->withInput($request->all());

            $i++;
        }

        if(!$valid){
            return redirect()->back()->withErrors('Detil harga wajib diisi!', 'default')->withInput($request->all());
        }

        // Check duplicate transaction
        $valid = Utilities::arrayIsUnique($courses);
        if(!$valid){
            return redirect()->back()->withErrors('Detil kelas tidak boleh kembar!', 'default')->withInput($request->all());
        }

        // Generate auto number
        if($request->filled('auto_number')){
            $sysNo = NumberingSystem::find(2);
            $trxCode = Utilities::GenerateNumber($sysNo->document, $sysNo->next_no);

            // Check existing number
            if(TransactionHeader::where('code', $trxCode)->exists()){
                return redirect()->back()->withErrors('Nomor Transaksi sudah terdaftar!', 'default')->withInput($request->all());
            }

            $sysNo->next_no++;
            $sysNo->save();
        }
        else{
            $trxCode = $request->input('code');

            // Check existing number
            if(TransactionHeader::where('code', $trxCode)->exists()){
                return redirect()->back()->withErrors('Nomor Transaksi sudah terdaftar!', 'default')->withInput($request->all());
            }
        }

        // Generate invoice number
        $sysNoInvoice = NumberingSystem::find(1);
        $invNumber = Utilities::GenerateNumber($sysNoInvoice->document, $sysNoInvoice->next_no);
        $sysNoInvoice->next_no++;
        $sysNoInvoice->save();

        $user = Auth::user();
        $now = Carbon::now('Asia/Jakarta');


        if(!empty($request->input('is_new_student'))){
            // Create student if new
            if(!empty($request->input('dob'))){
                $dob = Carbon::createFromFormat('d M Y', $request->input('dob'), 'Asia/Jakarta');
            }

            $newStudent = Customer::create([
                'name'          => $request->input('student_name'),
                'phone'         => $request->input('student_phone') ?? null,
                'email'         => $request->input('student_email'),
                'address'       => $request->input('student_address') ?? null,
                'dob'           => $dob->toDateTimeString() ?? null,
                'parent_name'   => $request->input('student_parent_name') ?? null
            ]);

            $customerId = $newStudent->id;
        }
        else{
            // Get existing student id
            $customerId = $request->input('customer_id');
        }

        $date = Carbon::createFromFormat('d M Y', $request->input('date'), 'Asia/Jakarta');

        $trxHeader = TransactionHeader::create([
            'code'                  => $trxCode,
            'type'                  => 1,
            'customer_id'           => $customerId,
            'date'                  => $date->toDateTimeString(),
            'payment_method'        => $request->input('payment_method'),
            'invoice_number'        => $invNumber,
            'status_id'             => 1,
            'created_by'            => $user->id,
            'created_at'            => $now->toDateTimeString(),
            'updated_by'            => $user->id,
            'updated_at'            => $now->toDateTimeString()
        ]);

        // Create transaction detail
        $totalPrice = 0;
        $totalDiscount = 0;
        $totalPayment = 0;
        $idx = 0;

        foreach($courses as $course){
            if(!empty($course)){
                $priceStr = str_replace('.','', $prices[$idx]);
                $price = (double) $priceStr;

                // Create new schedule
                $courseData = Course::find($course);
                $meetingAmount = 0;
                if($courseData->type == 2 || $courseData->type == 4) {

                    // Get next month date at 10th
                    $nextMonthDate = $now->copy()->addMonthsNoOverflow(1);
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

                // Gymastic class logic
                $scheduleDb = Schedule::where('customer_id', $request->get('customer_id'))
                    ->where('course_id', $course)
                    ->where('status_id', 2)
                    ->first();

                if($courseData->type == 4 && $courseData->meeting_amount == 8 && !empty($scheduleDb)){
                    $selectedDay = $scheduleDb->day;
                    if(strpos($selectedDay, $days[$i]) === false){
                        $selectedDay .= " & ".$days[$i];
                        $scheduleDb->day = $selectedDay;
                        $scheduleDb->save();

                    }

                    $scheduleObj = $scheduleDb;
                }
                else{
                    $newSchedule = Schedule::create([
                        'customer_id'       => $customerId,
                        'course_id'         => $course,
                        'day'               => $days[$i],
                        'start_date'        => $now->toDateTimeString(),
                        'finish_date'       => $finish->toDateTimeString(),
                        'meeting_amount'    => $meetingAmount,
                        'month_amount'      => 1,
                        'status_id'         => 2,
                        'created_by'        => $user->id,
                        'created_at'        => $now->toDateTimeString(),
                        'updated_by'        => $user->id,
                        'updated_at'        => $now->toDateTimeString()
                    ]);

                    $scheduleObj = $newSchedule;
                }

                $trxDetail = TransactionDetail::create([
                    'header_id'             => $trxHeader->id,
                    'schedule_id'           => $scheduleObj->id,
                    'day'                   => $scheduleObj->day,
                    'meeting_attendeds'     => 0,
                    'price'                 => $priceStr
                ]);

                // Check discount
//                if(!empty($discounts[$idx]) && $discounts[$idx] !== '0'){
//                    $discountStr = str_replace('.','', $discounts[$idx]);
//                    $trxDetail->discount = $discountStr;
//
//                    $discount = (double) $discountStr;
//                    $trxDetail->subtotal = $price - $discount;
//
//                    // Accumulate total price
//                    $totalPrice += $price;
//
//                    // Accumulate total discount
//                    $totalDiscount += $discount;
//                }
//                else{
//                    $trxDetail->subtotal = $price;
//                    $totalPrice += $price;
//                }

                $trxDetail->subtotal = $price;
                $totalPrice += $price;

                $trxDetail->save();

                // Accumulate subtotal
                $totalPayment += $trxDetail->subtotal;

                // Activate schedule
                $scheduleObj->status_id = 3;
                $scheduleObj->save();
            }
            $idx++;
        }

        if($totalDiscount > 0) $trxHeader->total_discount = $totalDiscount;

        if($request->filled('registration_fee')){
            $fee = str_replace('.','', $request->input('registration_fee'));
        }
        else{
            $fee = 0;
        }

        $totalPayment += $fee;
        $trxHeader->total_price = $totalPrice;
        $trxHeader->total_payment = $totalPayment;
        $trxHeader->registration_fee = $fee;
        $trxHeader->save();

        Session::flash('message', 'Berhasil membuat transaksi!');

        return redirect()->route('admin.transactions.show', ['transaction' => $trxHeader]);
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
     * Show the form for editing the specified transaction.
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

        return view('admin.transactions.edit')->with($data);
    }

    /**
     * Update the specified transaction in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param TransactionHeader $transaction
     * @return mixed
     */
    public function update(Request $request, TransactionHeader $transaction)
    {
        $validator = Validator::make($request->all(),[
            'date'              => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::user();
        $now = Carbon::now('Asia/Jakarta');

        $date = Carbon::createFromFormat('d M Y', $request->input('date'), 'Asia/Jakarta');
        $transaction->date = $date->toDateTimeString();

        $oldRegFee = $transaction->registration_fee;

        if($request->filled('registration_fee')){
            $regFeeStr = str_replace('.','', $request->input('registration_fee'));
            $regFee = (double) $regFeeStr;
        }
        else{
            $regFee = 0;
        }

        $transaction->payment_method = $request->input('payment_method');
        $transaction->registration_fee = $regFee;
        $transaction->total_payment = $transaction->total_payment - $oldRegFee + $regFee;

        $transaction->updated_by = $user->id;
        $transaction->updated_at = $now->toDateTimeString();

        $transaction->save();

        Session::flash('message', 'Berhasil mengubah transaksi!');

        return redirect()->route('admin.transactions.show', ['transaction' => $transaction]);
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

    public function report(){
        return View('admin.transactions.report', compact('departments'));
    }

    public function downloadReport(Request $request) {
        ini_set('max_execution_time', '3000');
        ini_set('memory_limit', '2048M');

        $validator = Validator::make($request->all(),[
            'start_date'        => 'required',
            'end_date'          => 'required'
        ],[
            'start_date.required'   => 'Dari Tanggal wajib diisi!',
            'end_date.required'     => 'Sampai Tanggal wajib diisi!'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $start = Carbon::createFromFormat('d M Y', $request->input('start_date'), 'Asia/Jakarta');
        $end = Carbon::createFromFormat('d M Y', $request->input('end_date'), 'Asia/Jakarta');

        // Validate date
        if($start->gt($end)){
            return redirect()->back()->withErrors('Dari Tanggal tidak boleh lebih besar dari Sampai Tanggal!', 'default')->withInput($request->all());
        }

        $start = $start->addDays(-1);
        $end = $end->addDays(1);

        $type = $request->input('class_type');
        if($type == 0){
            $headers = TransactionHeader::whereBetween('date', array($start->toDateTimeString(), $end->toDateTimeString()));
        }
        else{
            $headers = TransactionHeader::whereBetween('date', array($start->toDateTimeString(), $end->toDateTimeString()))
                ->whereHas('transaction_details', function ($query) use ($type){
                    $query->whereHas('schedule', function($query) use ($type){
                        $query->whereHas('course', function ($query) use ($type){
                            $query->where('type', $type);
                        });
                    });
                });
        }

        $headers = $headers->orderByDesc('date')
            ->get();

        // Validate Data
        if($headers->count() == 0){
            return redirect()->back()->withErrors('Data tidak ditemukan!', 'default')->withInput($request->all());
        }

        $total = $headers->sum('total_payment');
        $totalStr = number_format($total, 0, ",", ".");

        $data =[
            'header'            => $headers,
            'start_date'        => $request->input('start_date'),
            'finish_date'       => $request->input('end_date'),
            'total'             => $totalStr,
            'type'              => $type
        ];

        //return view('documents.purchase_orders.purchase_orders_pdf')->with($data);

//        $pdf = PDF::loadView('documents.transactions.trx_report', $data)
//            ->setPaper('a4', 'portrait');
//        $now = Carbon::now('Asia/Jakarta');
//        $filename = 'DMC_TRANSACTION_REPORT_' . $now->toDateTimeString();
//        $pdf->setOptions(["isPhpEnabled"=>true]);
//
//        return $pdf->download($filename.'.pdf');

        $pdf = PDF2::loadView('documents.transactions.trx_report', $data)
            ->setOption('footer-right', '[page] of [toPage]');

        $now = Carbon::now('Asia/Jakarta');
        $filename = 'DMC_TRANSACTION_REPORT_' . $now->toDateTimeString();

        return $pdf->download($filename. '.pdf');
    }

    public function printDocument($id){
        $header = TransactionHeader::find($id);
        $dateNow = Carbon::now('Asia/Jakarta');
        $now = $dateNow->format('d-M-Y');

        $data = [
            'header'         => $header,
            'now'            => $now
        ];

        return view('documents.transactions.invoice_doc')->with($data);
    }
}
