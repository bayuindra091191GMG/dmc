<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 15/10/2018
 * Time: 15:01
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Libs\Utilities;
use App\Models\Customer;
use App\Models\NumberingSystem;
use App\Models\Schedule;
use App\Models\TransactionDetail;
use App\Models\TransactionHeader;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class TransactionCutiHeaderController extends Controller
{
    public function create()
    {
        $customer = null;
        if(!empty(request()->customer)){
            $customer = Customer::find(request()->customer);
        }

        // Numbering System
        $sysNo = NumberingSystem::find(2);
        $autoNumber = Utilities::GenerateNumberPurchaseOrder($sysNo->document, $sysNo->next_no);

        $data = [
            'customer'          => $customer,
            'autoNumber'        => $autoNumber
        ];

        return view('admin.transactions.cuti.create')->with($data);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'code'              => 'required|max:45|regex:/^\S*$/u',
            'date'              => 'required'
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

        if(empty($schedules) || count($schedules) == 0){
            return redirect()->back()->withErrors('Detail kelas wajib diisi!', 'default')->withInput($request->all());
        }

        $months = $request->input('month');
        $valid = true;
        $i = 0;
        foreach($schedules as $schedule){
            if(empty($schedule)){ $valid = false; }
            if(empty($months)) $valid = false;
            $i++;
        }

        if(!$valid){
            return redirect()->back()->withErrors('Detail cuti dan lama cuti wajib diisi!', 'default')->withInput($request->all());
        }

        // Check duplicate inventory
        $valid = Utilities::arrayIsUnique($schedules);
        if(!$valid){
            return redirect()->back()->withErrors('Detail transaksi tidak boleh kembar!', 'default')->withInput($request->all());
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

        $date = Carbon::createFromFormat('d M Y', $request->input('date'), 'Asia/Jakarta');

        $trxHeader = TransactionHeader::create([
            'code'                  => $trxCode,
            'type'                  => 4,
            'invoice_number'        => $invNumber,
            'customer_id'           => $request->input('customer_id'),
            'date'                  => $date->toDateTimeString(),
            'payment_method'        => $request->input('payment_method'),
            'status_id'             => 1,
            'created_by'            => $user->id,
            'created_at'            => $now->toDateTimeString(),
            'updated_by'            => $user->id,
            'updated_at'            => $now->toDateTimeString()
        ]);

        // Create transaction detail
        $totalPayment = 0;
        $idx = 0;

        foreach($schedules as $schedule){
            if(!empty($schedule)){
                $scheduleObj = Schedule::find($schedule);
                $monthInt = (int) $months($idx);
                $subTotal = $monthInt * 150000;
                TransactionDetail::create([
                    'header_id'             => $trxHeader->id,
                    'schedule_id'           => $schedule,
                    'day'                   => $scheduleObj->day,
                    'month_amount'          => $monthInt,
                    'meeting_attendeds'     => 0,
                    'price'                 => 150000,
                    'subtotal'              => $subTotal
                ]);

                // Accumulate subtotal
                $totalPayment += $subTotal;

                // Activate schedule
                $scheduleObj->status_id = 7;
            }
            $idx++;
        }

        $trxHeader->total_price = $totalPayment;
        $trxHeader->total_payment = $totalPayment;
        $trxHeader->registration_fee = 0;
        $trxHeader->save();

        Session::flash('message', 'Berhasil membuat transaksi cuti!');

        return redirect()->route('admin.transactions.show', ['transaction' => $trxHeader]);
    }
}