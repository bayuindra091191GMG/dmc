<?php
/**
 * Created by PhpStorm.
 * User: hellb
 * Date: 2/18/2018
 * Time: 4:45 PM
 */

namespace App\Http\Controllers\Admin\Purchasing;


use App\Http\Controllers\Controller;
use App\Libs\Utilities;
use App\Models\NumberingSystem;
use App\Models\PaymentRequest;
use App\Models\PaymentRequestsPiDetail;
use App\Models\PaymentRequestsPoDetail;
use App\Models\PurchaseInvoiceHeader;
use App\Models\PurchaseOrderDetail;
use App\Models\PurchaseOrderHeader;
use App\Models\PurchaseRequestHeader;
use App\Models\Supplier;
use App\Transformer\Purchasing\PaymentRequestTransformer;
use App\Transformer\Purchasing\PurchaseInvoiceTransformer;
use App\Transformer\Purchasing\PurchaseOrderHeaderTransformer;
use Carbon\Carbon;
use Faker\Provider\Payment;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use PDF;

class PaymentRequestController extends Controller
{
    public function index(){

        return View('admin.purchasing.payment_requests.index');
    }

    public function chooseVendor(){
        return View('admin.purchasing.payment_requests.choose_vendor');
    }

    public function chooseVendorPo(){
        return View('admin.purchasing.payment_requests.choose_vendor_po');
    }

    public function beforeCreateFromPi(){
        $supplier = null;
        if(!empty(request()->supplier)){
            $supplier = Supplier::find(request()->supplier);
        }

        return View('admin.purchasing.payment_requests.before_create_from_pi', compact('supplier'));
    }

    public function beforeCreateFromPo(){
        $supplier = null;
        if(!empty(request()->supplier)){
            $supplier = Supplier::find(request()->supplier);
        }

        return View('admin.purchasing.payment_requests.before_create_from_po', compact('supplier'));
    }

    public function createFromPi(Request $request){
        $ids = $request->input('ids');
        $purchaseInvoices = PurchaseInvoiceHeader::whereIn('id', $ids)->get();

        // Numbering System
        $sysNo = NumberingSystem::where('doc_id', '7')->first();
        $autoNumber = Utilities::GenerateNumber('PMT', $sysNo->next_no);

        $data = [
            'purchaseInvoices'   => $purchaseInvoices,
            'autoNumber'        => $autoNumber
        ];

        return View('admin.purchasing.payment_requests.create')->with($data);
    }

    public function createFromPo(Request $request){
        $ids = $request->input('ids');
        $purchaseOrders = PurchaseOrderHeader::whereIn('id', $ids)->get();

        // Numbering System
        $sysNo = NumberingSystem::where('doc_id', '7')->first();
        $autoNumber = Utilities::GenerateNumber('PMT', $sysNo->next_no);

        $data = [
            'purchaseOrders'   => $purchaseOrders,
            'autoNumber'        => $autoNumber
        ];

        return View('admin.purchasing.payment_requests.create')->with($data);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'code'          => 'max:45|regex:/^\S*$/u',
            'bank_name'     => 'required',
            'account_no'    => 'required',
            'account_name'  => 'required',
            'date'          => 'required',
        ],[
            'code.regex'     => 'Nomor PO harus tanpa spasi!'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Validate Payment Request number
        if(empty(Input::get('auto_number')) && (empty(Input::get('code')) || Input::get('code') == "")){
            return redirect()->back()->withErrors('Nomor PO wajib diisi!', 'default')->withInput($request->all());
        }

        // Generate auto number
        if(Input::get('auto_number')){
            $sysNo = NumberingSystem::where('doc_id', '4')->first();
            $code = Utilities::GenerateNumber($sysNo->document->code, $sysNo->next_no);
            $sysNo->next_no++;
            $sysNo->save();
        }
        else{
            $code = Input::get('code');
        }

        // Check existing number
        $temp = PaymentRequest::where('code', $code)->first();
        if(!empty($temp)){
            return redirect()->back()->withErrors('Nomor Payment Request sudah terdaftar!', 'default')->withInput($request->all());
        }

        $ids = $request->input('item');
        $flag = $request->input('flag');
        $ppn = 0;
        $pph_23 = 0;
        $total_amount = 0;
        $amount = 0;

        if($flag == "pi"){
            foreach($ids as $id){
                $temp = PurchaseInvoiceHeader::find($id);
                $ppn += $temp->ppn_amount;
                $pph_23 += $temp->pph_amount;
                $amount += $temp->total_price;
                $total_amount += $temp->total_payment;
            }
        }
        else{
            foreach($ids as $id){
                $temp = PurchaseOrderHeader::find($id);
                $ppn += $temp->ppn_amount;
                $pph_23 += $temp->pph_amount;
                $amount += $temp->total_price;
                $total_amount += $temp->total_payment;
            }
        }

        $user = \Auth::user();
        $now = Carbon::now('Asia/Jakarta');
        $date = Carbon::createFromFormat('d M Y', $request->input('date'), 'Asia/Jakarta');

        $paymentRequest = PaymentRequest::create([
            'code'                      => $code,
            'date'                      => $date,
            'amount'                    => $amount,
            'total_amount'              => $total_amount,
            'requester_bank_name'       => $request->input('bank_name'),
            'requester_bank_account'    => $request->input('account_no'),
            'requester_account_name'    => $request->input('account_name'),
            'note'                      => $request->input('note'),
            'type'                      => $request->input('type'),
            'status_id'                 => 3,
            'created_by'                => $user->id,
            'created_at'                => $now->toDateTimeString()
        ]);

        //Check if DP or CBD
        $type = $request->input('type');
        if($type == "db" || $type == "cbd"){
            $paymentRequest->ppn = 0;
            $paymentRequest->pph_23 = 0;
        }
        else{
            $paymentRequest->ppn = $ppn;
            $paymentRequest->pph_23 = $pph_23;
        }

        $paymentRequest->save();

        // Create Payment Request detail
        if($flag == "pi"){
            $purchaseInvoices = PurchaseInvoiceHeader::whereIn('id', $ids)->get();
            foreach($purchaseInvoices as $detail){
                //create detail
                $prDetail = PaymentRequestsPiDetail::create([
                    'payment_requests_id'           => $paymentRequest->id,
                    'purchase_invoice_header_id'    => $detail->id
                ]);

                $prDetail->save();
            }
        }
        else{
            $purchaseOrders = PurchaseOrderHeader::whereIn('id', $ids)->get();
            foreach($purchaseOrders as $detail){
                //create detail
                $prDetail = PaymentRequestsPoDetail::create([
                    'payment_requests_id'  => $paymentRequest->id,
                    'purchase_order_id'    => $detail->id
                ]);

                $prDetail->save();
            }
        }

        Session::flash('message', 'Berhasil membuat Payment Request!');

        return redirect()->route('admin.payment_requests.show', ['payment_request' => $paymentRequest]);
    }

    public function show(PaymentRequest $paymentRequest){
        $date = Carbon::parse($paymentRequest->date)->format('d M Y');

        $purchaseInvoices = PurchaseInvoiceHeader::where('payment_requests_id', $paymentRequest->id)->get();
        $purchaseOrders = PurchaseOrderHeader::where('payment_requests_id', $paymentRequest->id)->get();

        $flag = "po";
        if($purchaseInvoices != null){
            $flag = "pi";
        }

        $data = [
            'header'            => $paymentRequest,
            'purchaseInvoices'  => $purchaseInvoices,
            'purchaseOrders'    => $purchaseOrders,
            'flag'              => $flag,
            'date'              => $date
        ];

        return View('admin.purchasing.payment_requests.show')->with($data);
    }

    public function report(){
        return View('admin.purchasing.purchase_orders.report');
    }

    public function downloadReport(Request $request) {
        //Get Data First
        $tempStart = strtotime(Input::get('start_date'));
        $start = date('Y-m-d', $tempStart);
        $tempEnd = strtotime(Input::get('end_date'));
        $end = date('Y-m-d', $tempEnd);

        //Check date
        if($start > $end){
            return redirect()->back()->withErrors('Start Date Tidak boleh lebih besar dari Finish Date!', 'default')->withInput($request->all());
        }

        $data = PurchaseOrderHeader::whereBetween('created_at', array($start, $end))->get();

        //Check Data
        if($data == null || $data->count() == 0){
            return redirect()->back()->withErrors('Data Tidak Ditemukan!', 'default')->withInput($request->all());
        }

        $total = 0;
        foreach ($data as $item){
            $total += $item->total_payment;
        }
        $totalStr = 'Rp '. number_format($total, 0, ",", ".");

        $pdf = PDF::loadView('documents.purchase_orders.purchase_orders_pdf', ['data' => $data, 'start_date' => Input::get('start_date'), 'finish_date' => Input::get('end_date'), 'total' => $totalStr])
            ->setPaper('a4', 'landscape');
        $now = Carbon::now('Asia/Jakarta');
        $filename = 'PURCHASE_ORDER_REPORT_' . $now->toDateTimeString();

        return $pdf->download($filename.'.pdf');
    }

    public function getIndex(){
        try{
            $paymentRequests = PaymentRequest::dateDescending()->get();
            return DataTables::of($paymentRequests)
                ->setTransformer(new PaymentRequestTransformer())
                ->addIndexColumn()
                ->make(true);
        }
        catch(\Exception $ex){
            error_log($ex);
        }
    }

    public function printDocument($id){
        $purchaseOrder = PurchaseOrderHeader::find($id);
        $purchaseOrderDetails = PurchaseOrderDetail::where('header_id', $purchaseOrder->id)->get();

        return view('documents.purchase_orders.purchase_orders_doc', compact('purchaseOrder', 'purchaseOrderDetails'));
    }

    public function download($id){
        $purchaseOrder = PurchaseOrderHeader::find($id);
        $purchaseOrderDetails = PurchaseOrderDetail::where('header_id', $purchaseOrder->id)->get();

        $pdf = PDF::loadView('documents.purchase_orders.purchase_orders_doc', ['purchaseOrder' => $purchaseOrder, 'purchaseOrderDetails' => $purchaseOrderDetails])->setPaper('A4');
        $now = Carbon::now('Asia/Jakarta');
        $filename = $purchaseOrder->code. '_' . $now->toDateTimeString();

        return $pdf->download($filename.'.pdf');
    }
}