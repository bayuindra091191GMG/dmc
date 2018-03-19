<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 07/02/2018
 * Time: 10:22
 */

namespace App\Http\Controllers\Admin\Purchasing;


use App\Http\Controllers\Controller;
use App\Libs\Utilities;
use App\Models\Department;
use App\Models\Document;
use App\Models\NumberingSystem;
use App\Models\PurchaseOrderHeader;
use App\Models\PurchaseRequestDetail;
use App\Models\PurchaseRequestHeader;
use App\Transformer\Purchasing\PurchaseRequestHeaderTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use PDF;

class PurchaseRequestHeaderController extends Controller
{
    public function index(){

        return View('admin.purchasing.purchase_requests.index');
    }

    public function create(){
        $departments = Department::all();

        // Numbering System
        $sysNo = NumberingSystem::where('doc_id', '3')->first();
        $autoNumber = Utilities::GenerateNumber('PR', $sysNo->next_no);

        $data = [
            'departments'   => $departments,
            'autoNumber'    => $autoNumber
        ];

        return View('admin.purchasing.purchase_requests.create')->with($data);
    }

    public function show(PurchaseRequestHeader $purchase_request){
        $header = $purchase_request;
        $date = Carbon::parse($purchase_request->date)->format('d M Y');

        // Numbering System
        $sysNo = NumberingSystem::where('doc_id', '3')->first();
        $autoNumber = Utilities::GenerateNumber($sysNo->document->code, $sysNo->next_no);

        $data = [
            'header'        => $header,
            'autoNumber'    => $autoNumber,
            'date'          => $date
        ];

        return View('admin.purchasing.purchase_requests.show')->with($data);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'pr_code'       => 'required|max:30',
            'km'            => 'max:20',
            'hm'            => 'max:20',
            'date'          => 'required'
        ],[
            'code.required'     => 'Nomor PR wajib diisi!'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Validate PR number
//        if(empty(Input::get('auto_number')) && (empty(Input::get('pr_code')) || Input::get('pr_code') == "")){
//            return redirect()->back()->withErrors('Nomor PR wajib diisi!', 'default')->withInput($request->all());
//        }

        // Validate department
        if($request->input('department') === '-1'){
            return redirect()->back()->withErrors('Pilih departemen!', 'default')->withInput($request->all());
        }

        // Validate priority
        if($request->input('priority') === '-1'){
            return redirect()->back()->withErrors('Pilih prioritas!', 'default')->withInput($request->all());
        }

        // Generate auto number
        $prCode = 'default';
        if($request->filled('auto_number')){
            $sysNo = NumberingSystem::where('doc_id', '3')->first();
            $prCode = Utilities::GenerateNumber($sysNo->document->code, $sysNo->next_no);
            $sysNo->next_no++;
            $sysNo->save();
        }
        else{
            $prCode = $request->input('pr_code');
        }

        // Check existing number
        if(PurchaseOrderHeader::where('code', $prCode)->exists()){
            return redirect()->back()->withErrors('Nomor PR sudah terdaftar!', 'default')->withInput($request->all());
        }

        // Validate details
        $items = $request->input('item');

        if(count($items) == 0){
            return redirect()->back()->withErrors('Detail barang wajib diisi!', 'default')->withInput($request->all());
        }

        $qtys = $request->input('qty');
        $valid = true;
        $i = 0;
        foreach($items as $item){
            if(empty($item)) $valid = false;
            if(empty($qtys[$i]) || $qtys[$i] == '0') $valid = false;
            $i++;
        }

        if(!$valid){
            return redirect()->back()->withErrors('Detail barang dan jumlah wajib diisi!', 'default')->withInput($request->all());
        }

        $user = \Auth::user();
        $now = Carbon::now('Asia/Jakarta');

        $prHeader = PurchaseRequestHeader::create([
            'code'              => $prCode,
            'department_id'     => $request->input('department'),
            'priority'          => $request->input('priority'),
            'km'                => $request->input('km'),
            'hm'                => $request->input('hm'),
            'status_id'         => 3,
            'created_by'        => $user->id,
            'created_at'        => $now->toDateTimeString()

        ]);

        if($request->filled('machinery')){
            $prHeader->machinery_id = $request->input('machinery');
            $prHeader->save();
        }

        $date = Carbon::createFromFormat('d M Y', $request->input('date'), 'Asia/Jakarta');
        $prHeader->date = $date->toDateTimeString();
        $prHeader->save();

        // Create purchase request detail
        $qty = $request->input('qty');
        $remark = $request->input('remark');
        $idx = 0;
        foreach($request->input('item') as $item){
            if(!empty($item)){
                $prDetail = PurchaseRequestDetail::create([
                    'header_id'     => $prHeader->id,
                    'item_id'       => $item,
                    'quantity'      => $qty[$idx]
                ]);

                if(!empty($remark[$idx])) $prDetail->remark = $remark[$idx];
                $prDetail->save();
            }
            $idx++;
        }

        Session::flash('message', 'Berhasil membuat purchase request!');

        return redirect()->route('admin.purchase_requests.show', ['purchase_request' => $prHeader]);
    }

    public function edit(PurchaseRequestHeader $purchase_request){
        $header = $purchase_request;
        $departments = Department::all();
        $date = Carbon::parse($purchase_request->date)->format('d M Y');

        $data = [
            'header'        => $header,
            'departments'   => $departments,
            'date'          => $date
        ];

        return View('admin.purchasing.purchase_requests.edit')->with($data);
    }

    public function update(Request $request, PurchaseRequestHeader $purchase_request){
        $validator = Validator::make($request->all(),[
            'km'            => 'max:20',
            'hm'            => 'max:20',
            'date'          => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Validate department
        if($request->input('department') === '-1'){
            return redirect()->back()->withErrors('Pilih departemen!', 'default')->withInput($request->all());
        }

        // Validate priority
        if($request->input('priority') === '-1'){
            return redirect()->back()->withErrors('Pilih prioritas!', 'default')->withInput($request->all());
        }

        $user = \Auth::user();
        $now = Carbon::now('Asia/Jakarta');
        $date = Carbon::createFromFormat('d M Y', $request->input('date'), 'Asia/Jakarta');

        $purchase_request->department_id = $request->input('department');
        $purchase_request->priority = $request->input('priority');
        $purchase_request->km = $request->input('km');
        $purchase_request->hm = $request->input('hm');
        $purchase_request->date = $date;
        $purchase_request->updated_by = $user->id;
        $purchase_request->updated_at = $now->toDateTimeString();

        if($request->filled('machinery')){
            $purchase_request->machinery_id = $request->input('machinery');
        }

        $purchase_request->save();

        Session::flash('message', 'Berhasil ubah purchase request!');

        return redirect()->route('admin.purchase_requests.show', ['purchase_request' => $purchase_request]);
    }

    public function report(){
        return View('admin.purchasing.purchase_requests.report');
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

        $data = PurchaseRequestHeader::whereBetween('created_at', array($start, $end))->get();

        //Check Data
        if($data == null || $data->count() == 0){
            return redirect()->back()->withErrors('Data Tidak Ditemukan!', 'default')->withInput($request->all());
        }

        $pdf = PDF::loadView('documents.purchase_requests.purchase_requests_pdf', ['data' => $data, 'start_date' => Input::get('start_date'), 'finish_date' => Input::get('end_date')])
            ->setPaper('a4', 'landscape');
        $now = Carbon::now('Asia/Jakarta');
        $filename = 'PURCHASE_REQUEST_REPORT_' . $now->toDateTimeString();

        return $pdf->download($filename.'.pdf');
    }

    public function download($id){
        $purchaseRequest = PurchaseRequestHeader::find($id);
        $purchaseRequestDetails = PurchaseRequestDetail::where('header_id', $purchaseRequest->id)->get();

        $pdf = PDF::loadView('documents.purchase_requests.purchase_requests_doc', ['purchaseRequest' => $purchaseRequest, 'purchaseRequestDetails' => $purchaseRequestDetails])->setPaper('A4');
        $now = Carbon::now('Asia/Jakarta');
        $filename = $purchaseRequest->code. '_' . $now->toDateTimeString();

        return $pdf->download($filename.'.pdf');
    }

    public function printDocument($id){
        $purchaseRequest = PurchaseRequestHeader::find($id);
        $purchaseRequestDetails = PurchaseRequestDetail::where('header_id', $purchaseRequest->id)->get();

        return view('documents.purchase_requests.purchase_requests_doc', compact('purchaseRequest', 'purchaseRequestDetails'));
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function getIndex(Request $request){
        $purchaseRequests = PurchaseRequestHeader::all();

        $mode = 'default';
        if($request->filled('mode')){
            $mode = $request->input('mode');
        }

        return DataTables::of($purchaseRequests)
            ->setTransformer(new PurchaseRequestHeaderTransformer($mode))
            ->addIndexColumn()
            ->make(true);
    }

    public function getPurchaseRequests(Request $request){
        $term = trim($request->q);
        $purchase_requests = PurchaseRequestHeader::where('code', 'LIKE', '%'. $term. '%')
            ->get();

        $formatted_tags = [];

        foreach ($purchase_requests as $purchase_request) {
            $formatted_tags[] = ['id' => $purchase_request->id, 'text' => $purchase_request->code];
        }

        return \Response::json($formatted_tags);
    }
}