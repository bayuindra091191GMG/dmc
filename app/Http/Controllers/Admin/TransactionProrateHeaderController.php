<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 11/07/2018
 * Time: 14:04
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Libs\Utilities;
use App\Models\Customer;
use App\Models\NumberingSystem;
use App\Models\Schedule;
use App\Models\TransactionDetail;
use App\Models\TransactionHeader;
use App\Transformer\MasterData\TransactionHeaderTranformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class TransactionProrateHeaderController extends Controller
{
    public function index()
    {
        return view('admin.transactions.prorate.index');
    }

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

        return view('admin.transactions.prorate.create')->with($data);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'code'              => 'required|max:45|regex:/^\S*$/u',
            'date'              => 'required',
            'registration_fee'  => 'required'
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
            return redirect()->back()->withErrors('Nomor Transaksi wajib diisi!', 'default')->withInput($request->all());
        }

        // Validate details
        $schedules = $request->input('schedule');
        $prorate = $request->input('prorate');
        $prices = $request->input('price');
        $normPrices = $request->input('normal_price');
        $discounts = $request->input('discount');
        $valid = true;
        $i = 0;
        foreach($schedules as $schedule){
            if(empty($schedule)) $valid = false;
            if(empty($prorate)) $valid = false;
            if(empty($prices[$i]) || $prices[$i] == '0') $valid = false;

            // Validate discount
            $priceVad = str_replace('.','', $prices[$i]);
            $discountVad = str_replace('.','', $discounts[$i]);
            if((double) $discountVad > (double) $priceVad) return redirect()->back()->withErrors('Diskon tidak boleh melebihi harga!', 'default')->withInput($request->all());

            $i++;
        }

        if(!$valid){
            return redirect()->back()->withErrors('Detail prorate dan harga wajib diisi!', 'default')->withInput($request->all());
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

        $trxHeader = TransactionHeader::create([
            'code'                  => $trxCode,
            'type'                  => 2,
            'customer_id'           => $request->input('customer_id'),
            'invoice_number'        => $invNumber,
            'status_id'             => 1,
            'created_by'            => $user->id,
            'created_at'            => $now->toDateTimeString(),
            'updated_by'            => $user->id,
            'updated_at'            => $now->toDateTimeString()
        ]);

        $date = Carbon::createFromFormat('d M Y', $request->input('date'), 'Asia/Jakarta');
        $trxHeader->date = $date->toDateTimeString();

        $trxHeader->save();

        // Create transaction detail
        $totalPrice = 0;
        $totalProratePrice = 0;
        $totalDiscount = 0;
        $totalPayment = 0;
        $idx = 0;

        foreach($schedules as $schedule){
            if(!empty($schedule)){
                $priceStr = str_replace('.','', $prices[$idx]);
                $proratePrice = (double) $priceStr;
                $normalPrice = (double) $normPrices[$idx];
                $scheduleObj = Schedule::find($schedule);
                $trxDetail = TransactionDetail::create([
                    'header_id'             => $trxHeader->id,
                    'schedule_id'           => $schedule,
                    'day'                   => $scheduleObj->day,
                    'prorate'               => $prorate[$idx],
                    'meeting_attendeds'     => 0,
                    'price'                 => $normalPrice,
                    'prorate_price'         => $proratePrice
                ]);

                // Check discount
                if(!empty($discounts[$idx]) && $discounts[$idx] !== '0'){
                    $discountStr = str_replace('.','', $discounts[$idx]);
                    $trxDetail->discount = $discountStr;

                    $discount = (double) $discountStr;
                    $trxDetail->subtotal = $proratePrice - $discount;

                    // Accumulate total discount
                    $totalDiscount += $discount;
                }
                else{
                    $trxDetail->subtotal = $proratePrice;
                }

                // Accumulate total normal price
                $totalPrice += $normalPrice;

                // Accumulate total prorate price
                $totalProratePrice += $proratePrice;

                $trxDetail->save();

                // Accumulate subtotal
                $totalPayment += $trxDetail->subtotal;

                // Activate schedule
                $scheduleObj->status_id = 3;
            }
            $idx++;
        }

        if($totalDiscount > 0) $trxHeader->total_discount = $totalDiscount;
        $fee = str_replace('.','', $request->input('registration_fee'));
        $totalPayment += (double) $fee;
        $trxHeader->total_price = $totalPrice;
        $trxHeader->total_prorate_price = $totalProratePrice;
        $trxHeader->total_payment = $totalPayment;
        $trxHeader->registration_fee = $fee;
        $trxHeader->save();

        Session::flash('message', 'Berhasil membuat transaksi prorate!');

        return redirect()->route('admin.transactions.show', ['transaction' => $trxHeader]);
    }

    public function edit(TransactionHeader $prorate)
    {
        $header = $prorate;
        $date = Carbon::parse($prorate->date)->format('d M Y');

        $data = [
            'header'    => $header,
            'date'      => $date
        ];

        return view('admin.transactions.prorate.edit')->with($data);
    }

    public function update(Request $request, TransactionHeader $transaction)
    {
        $validator = Validator::make($request->all(),[
            'date'              => 'required',
            'registration_fee'  => 'required'
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
        $regFeeStr = str_replace('.','', $request->input('registration_fee'));
        $regFee = (double) $regFeeStr;
        $transaction->registration_fee = $regFee;
        $transaction->total_payment = $transaction->total_payment - $oldRegFee + $regFee;

        $transaction->updated_by = $user->id;
        $transaction->updated_at = $now->toDateTimeString();

        $transaction->save();

        Session::flash('message', 'Berhasil mengubah transaksi!');

        return redirect()->route('admin.transactions.show', ['transaction' => $transaction]);
    }


    public function getIndex()
    {
        $headers = TransactionHeader::where('type', 2)->orderBy('created_at','desc')->get();
        return DataTables::of($headers)
            ->setTransformer(new TransactionHeaderTranformer)
            ->addIndexColumn()
            ->make(true);
    }
}