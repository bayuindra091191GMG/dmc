<?php

namespace App\Http\Controllers\Admin;

use App\Libs\Utilities;
use App\Models\Auth\User\User;
use App\Models\Coach;
use App\Models\Course;
use App\Models\Customer;
use App\Models\NumberingSystem;
use App\Models\Schedule;
use App\Models\TransactionDetail;
use App\Models\TransactionHeader;
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
use PDF;

class TransactionHeaderController extends Controller
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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

        return view('admin.transactions.create')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
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
        $prices = $request->input('price');
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
        $trxHeader->total_price = $totalPrice;
        $trxHeader->total_payment = $totalPayment;
        $trxHeader->registration_fee = $request->input('registration_fee');
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

        return view('admin.transactions.edit')->with($data);
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

        Session::flash('message', 'Berhasil mengubah transaksi!');

        return redirect()->route('admin.transactions.show', ['transaction' => $trxHeader]);
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
        $validator = Validator::make($request->all(),[
            'start_date'        => 'required',
            'end_date'          => 'required',
        ],[
            'start_date.required'   => 'Dari Tanggal wajib diisi!',
            'end_date.required'     => 'Sampai Tanggal wajib diisi!',

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

        $headers = TransactionHeader::whereBetween('date', array($start->toDateTimeString(), $end->toDateTimeString()));

        $headers = $headers->orderByDesc('date')
            ->get();

        // Validate Data
        if($headers->count() == 0){
            return redirect()->back()->withErrors('Data tidak ditemukan!', 'default')->withInput($request->all());
        }

        $total = $headers->sum('total_payment');
        $totalStr = number_format($total, 0, ",", ".");

        $data =[
            'header'         => $headers,
            'start_date'        => $request->input('start_date'),
            'finish_date'       => $request->input('end_date'),
            'total'             => $totalStr
        ];

        //return view('documents.purchase_orders.purchase_orders_pdf')->with($data);

        $pdf = PDF::loadView('documents.transactions.trx_report', $data)
            ->setPaper('a4', 'portrait');
        $now = Carbon::now('Asia/Jakarta');
        $filename = 'DMC_TRANSACTION_REPORT_' . $now->toDateTimeString();
        $pdf->setOptions(["isPhpEnabled"=>true]);

        return $pdf->download($filename.'.pdf');
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
