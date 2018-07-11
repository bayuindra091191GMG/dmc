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
use App\Models\TransactionHeader;
use App\Transformer\MasterData\TransactionHeaderTranformer;
use Illuminate\Http\Request;
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
            'code'        => 'required|max:45|regex:/^\S*$/u',
            'date'        => 'required'
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
            if(empty($prices[$i]) || $prices[$i] == '0') $valid = false;

            // Validate discount
            $priceVad = str_replace('.','', $prices[$i]);
            $discountVad = str_replace('.','', $discounts[$i]);
            if((double) $discountVad > (double) $priceVad) return redirect()->back()->withErrors('Diskon tidak boleh melebihi harga!', 'default')->withInput($request->all());

            $i++;
        }

        if(!$valid){
            return redirect()->back()->withErrors('Detail kuantitas dan harga wajib diisi!', 'default')->withInput($request->all());
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
        $totalDiscount = 0;
        $totalPayment = 0;
        $idx = 0;

        foreach($schedules as $schedule){
            if(!empty($schedule)){
                $priceStr = str_replace('.','', $prices[$idx]);
                $price = (double) $priceStr;
                $scheduleObj = Schedule::find($schedule);
                $trxDetail = TransactionDetail::create([
                    'header_id'             => $trxHeader->id,
                    'schedule_id'           => $schedule,
                    'day'                   => $scheduleObj->day,
                    'meeting_attendeds'     => 0,
                    'price'                 => $priceStr
                ]);

                // Check discount
                if(!empty($discounts[$idx]) && $discounts[$idx] !== '0'){
                    $discountStr = str_replace('.','', $discounts[$idx]);
                    $trxDetail->discount = $discountStr;

                    $discount = (double) $discountStr;
                    $trxDetail->subtotal = $price - $discount;

                    // Accumulate total price
                    $totalPrice += $price;

                    // Accumulate total discount
                    $totalDiscount += $discount;
                }
                else{
                    $trxDetail->subtotal = $price;
                    $totalPrice += $price;
                }

                $trxDetail->save();

                // Accumulate subtotal
                $totalPayment += $trxDetail->subtotal;
            }
            $idx++;
        }

        if($totalDiscount > 0) $trxHeader->total_discount = $totalDiscount;
        $fee = str_replace('.','', $request->input('registration_fee'));
        $totalPayment += $fee;
        $trxHeader->total_price = $totalPrice;
        $trxHeader->total_payment = $totalPayment;
        $trxHeader->registration_fee = $fee;
        $trxHeader->save();

        Session::flash('message', 'Berhasil membuat transaksi!');

        return redirect()->route('admin.transactions.show', ['transaction' => $trxHeader]);
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