<?php


namespace App\Http\Controllers\Admin\Registration;


use App\Http\Controllers\Controller;
use App\Libs\Utilities;
use App\Models\CourseDetail;
use App\Models\Customer;
use App\Models\CustomerVoucher;
use App\Models\NumberingSystem;
use App\Models\Schedule;
use App\Models\TransactionDetail;
use App\Models\TransactionHeader;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class RegistrationTransactionController extends Controller
{
    public function formStepThree(int $type, int $student_id){
        if($type === 1){
            $courseType = 'MUAYTHAI';
            //$backRoute = 'admin.registration.muaythai.step-one';
        }
        else if($type === 2){
            $courseType = 'DANCE';
            //$backRoute = 'admin.registration.dance.step-one';
        }
        else if($type === 3){
            $courseType = 'PRIVATE';
            //$backRoute = 'admin.registration.private.step-one';
        }
        else if($type === 4){
            $courseType = 'GYMNASTIC';
            //$backRoute = 'admin.registration.gymnastic.step-one';
        }
        else{
            $courseType = 'INVALID';
            //$viewPath = 'INVALID';
            //$backRoute = 'INVALID';
            dd('INVALID COURSE TYPE!');
        }

        $student = Customer::find($student_id);
        if(empty($student)){
            dd('INVALID STUDENT!');
        }

        // Numbering System
        $sysNo = NumberingSystem::find(2);
        $autoNumber = Utilities::GenerateNumberPurchaseOrder($sysNo->document, $sysNo->next_no);

        $today = Carbon::today('Asia/Jakarta')->format('d M y');

        // Get not active schedule
        $schedules = Schedule::where('customer_id', $student_id)
            ->whereHas('course', function($query) use ($type){
                $query->where('type', $type);
            })
            ->where('status_id', 2)
            ->get();

        $totalPrice = 0;
        foreach ($schedules as $schedule){
            $totalPrice += $schedule->course->price;
        }

        // Vouchers
        $vouchers = null;
        if(DB::table('customer_vouchers')->where('customer_id', $student_id)->exists()){
            $vouchers = CustomerVoucher::where('customer_id', $student_id)->where('status_id', 1)->get();
        }

        $data = [
            'type'              => $type,
            'courseType'        => $courseType,
            'student'           => $student,
            'autoNumber'        => $autoNumber,
            'today'             => $today,
            'schedules'         => $schedules,
            'vouchers'          => $vouchers,
            'totalPrice'        => $totalPrice
        ];

        return view('admin.registrations.transactions.step_3_transaction')->with($data);
    }

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
        $studentId = $request->input('customer_id');
        $type = $request->input('type');
        //dd($type);

        $date = Carbon::createFromFormat('d M Y', $request->input('date'), 'Asia/Jakarta');

        $trxHeader = TransactionHeader::create([
            'code'                  => $trxCode,
            'type'                  => 1,
            'customer_id'           => $studentId,
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
        $idx = 0;

        // Get not active schedule
//        $schedules = Schedule::where('customer_id', $studentId)
//            ->whereHas('course', function($query) use ($type){
//                $query->where('type', $type);
//            })
//            ->where('status_id', 2)
//            ->get();

        $scheduleIds = $request->input('schedule_ids');
        $months = $request->input('months');

        $idx = 0;
        foreach($scheduleIds as $scheduleId){
            $schedule = Schedule::find($scheduleId);
            if(!empty($schedule)){
                $month = floatval($months[$idx]);
                $trxDetail = TransactionDetail::create([
                    'header_id'             => $trxHeader->id,
                    'schedule_id'           => $schedule->id,
                    'day'                   => $schedule->day,
                    'month_amount'          => $month,
                    'meeting_attendeds'     => 0,
                    'price'                 => $schedule->course->price,
                    'discount'              => 0
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

                $totalPrice += ($month * $schedule->course->price);
                $trxDetail->subtotal = $month * $schedule->course->price;
                $trxDetail->meeting_amount = $schedule->course->meeting_amount;

                // Check more than 1 month payment
                if($month > 1){
                    if($schedule->course->type === 1){
                        $addedTimes = $month - 1;

                        $addedValid = $addedTimes * $schedule->course->valid;
                        $newFinishDate = Carbon::parse($schedule->finish_date)->addDays($addedValid);
                        $schedule->finish_date =  $newFinishDate->toDateTimeString();
                        $schedule->meeting_amount +=  ($addedTimes * $schedule->course->meeting_amount);

                        $trxDetail->meeting_amount += ($addedTimes * $schedule->course->meeting_amount);
                    }
                    else{
                        $addedMonth = $month - 1;
                        $newFinishDate = Carbon::parse($schedule->finish_date)->addMonthNoOverflow($addedMonth);
                        $schedule->finish_date =  $newFinishDate->toDateTimeString();

                        $trxDetail->meeting_amount += ($addedMonth * $schedule->course->meeting_amount);
                    }
                }

                if($request->filled('voucher_code') && $request->input('is_discount') == 1) {
                    if (DB::table('vouchers')->where('name', $request->input('voucher_code'))->exists()) {
                        $voucher = Voucher::where('name', $request->input('voucher_code'))->first();
                        if ($voucher->type == 'free_package') {
                            $trxDetail->meeting_amount += $voucher->free_package;
                        }

                        $customerVoucher = CustomerVoucher::where('voucher_id', $voucher->id)->where('customer_id', $studentId)->first();
                        $customerVoucher->status_id = 8;
                        $customerVoucher->save();
                    }
                }
                $trxDetail->save();

                // Activate schedule
                $schedule->status_id = 3;
                $schedule->save();

                // Increase student count
                if(($type === 2 || $type === 4) && $schedule->day != 'Bebas'){
                    $splitted = explode('-', $schedule->day);
                    $dayString = trim($splitted[0]);
                    $timeString = trim($splitted[1]);

                    $courseDetail = CourseDetail::where('course_id', $schedule->course_id)
                        ->where('day_name', $dayString)
                        ->where('time', $timeString)
                        ->first();
                    $courseDetail->current_capacity += 1;
                    $courseDetail->save();
                }
            }
            $idx++;
        }

//        if($totalDiscount > 0) $trxHeader->total_discount = $totalDiscount;
        $trxHeader->total_discount = 0;

        if($request->filled('registration_fee')){
            $fee = str_replace('.','', $request->input('registration_fee'));
        }
        else{
            $fee = 0;
        }

        $totalDiscount = 0;
        if($request->filled('voucher_code') && $request->input('is_discount') == 1) {
            if (DB::table('vouchers')->where('name', $request->input('voucher_code'))->exists()) {
                $voucher = Voucher::where('name', $request->input('voucher_code'))->first();
                if ($voucher->type == 'discount_percentage') {
                    $totalDiscount = $totalPrice * $voucher->discount_percentage / 100;
                } else if ($voucher->type == 'discount_total') {
                    $totalDiscount = $voucher->discount_total;
                }
                $customerVoucher = CustomerVoucher::where('voucher_id', $voucher->id)->where('customer_id', $studentId)->first();
                $customerVoucher->status_id = 8;
                $customerVoucher->save();
            }
        }

        $trxHeader->total_discount = $totalDiscount;
        $trxHeader->total_price = $totalPrice;
        $trxHeader->total_payment = ($totalPrice  - $totalDiscount) +  $fee;
        $trxHeader->registration_fee = $fee;
        $trxHeader->save();

        Session::flash('message', 'Berhasil membuat transaksi baru!');

        return redirect()->route('admin.transactions.show', ['transaction' => $trxHeader]);
    }

    public function formStepThreeProrate(int $type, int $student_id){
        if($type === 1){
            $courseType = 'MUAYTHAI';
            //$backRoute = 'admin.registration.muaythai.step-one';
        }
        else if($type === 2){
            $courseType = 'DANCE';
            //$backRoute = 'admin.registration.dance.step-one';
        }
        else if($type === 3){
            $courseType = 'PRIVATE';
            //$backRoute = 'admin.registration.private.step-one';
        }
        else if($type === 4){
            $courseType = 'GYMNASTIC';
            //$backRoute = 'admin.registration.gymnastic.step-one';
        }
        else{
            $courseType = 'INVALID';
            //$viewPath = 'INVALID';
            //$backRoute = 'INVALID';
            dd('INVALID COURSE TYPE!');
        }

        $customer = Customer::find($student_id);
        if(empty($customer)){
            dd('INVALID STUDENT');
        }

        // Numbering System
        $sysNo = NumberingSystem::find(2);
        $autoNumber = Utilities::GenerateNumberPurchaseOrder($sysNo->document, $sysNo->next_no);

        $data = [
            'type'              => $type,
            'courseType'        => $courseType,
            'customer'          => $customer,
            'autoNumber'        => $autoNumber
        ];

        return view('admin.registrations.transactions.step_3_transaction_prorate')->with($data);
    }

    public function formStepThreeCuti(int $type, int $student_id){
        if($type === 1){
            $courseType = 'MUAYTHAI';
            //$backRoute = 'admin.registration.muaythai.step-one';
        }
        else if($type === 2){
            $courseType = 'DANCE';
            //$backRoute = 'admin.registration.dance.step-one';
        }
        else if($type === 3){
            $courseType = 'PRIVATE';
            //$backRoute = 'admin.registration.private.step-one';
        }
        else if($type === 4){
            $courseType = 'GYMNASTIC';
            //$backRoute = 'admin.registration.gymnastic.step-one';
        }
        else{
            $courseType = 'INVALID';
            //$viewPath = 'INVALID';
            //$backRoute = 'INVALID';
            dd('INVALID COURSE TYPE!');
        }

        $customer = Customer::find($student_id);
        if(empty($customer)){
            dd('INVALID STUDENT');
        }

        // Numbering System
        $sysNo = NumberingSystem::find(2);
        $autoNumber = Utilities::GenerateNumberPurchaseOrder($sysNo->document, $sysNo->next_no);

        $data = [
            'type'              => $type,
            'courseType'        => $courseType,
            'customer'          => $customer,
            'autoNumber'        => $autoNumber
        ];

        return view('admin.registrations.transactions.step_3_transaction_prorate')->with($data);
    }
}