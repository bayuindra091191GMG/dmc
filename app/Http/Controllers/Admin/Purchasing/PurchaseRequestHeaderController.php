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
use App\Models\MaterialRequestHeader;
use App\Models\NumberingSystem;
use App\Models\PurchaseOrderHeader;
use App\Models\PurchaseRequestDetail;
use App\Models\PurchaseRequestHeader;
use App\Transformer\Purchasing\PurchaseRequestHeaderTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use PDF;
use Yajra\DataTables\DataTables;

class PurchaseRequestHeaderController extends Controller
{
    public function index(){

        return View('admin.purchasing.purchase_requests.index');
    }

    public function beforeCreate(){
        return View('admin.purchasing.purchase_requests.before_create');
    }

    public function create(){

        if(empty(request()->mr)){
            return redirect()->route('admin.purchase_requests.before_create');
        }

        $materialRequest = MaterialRequestHeader::find(request()->mr);
        $departments = Department::all();

        // Numbering System
        $sysNo = NumberingSystem::where('doc_id', '3')->first();
        $autoNumber = Utilities::GenerateNumber($sysNo->document->code, $sysNo->next_no);

        $data = [
            'departments'       => $departments,
            'autoNumber'        => $autoNumber,
            'materialRequest'   => $materialRequest
        ];

        return View('admin.purchasing.purchase_requests.create')->with($data);
    }

    public function show(PurchaseRequestHeader $purchase_request){
        $header = $purchase_request;
        $date = Carbon::parse($purchase_request->date)->format('d M Y');

        $data = [
            'header'        => $header,
            'date'          => $date
        ];

        return View('admin.purchasing.purchase_requests.show')->with($data);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'pr_code'       => 'required|max:30|regex:/^\S*$/u',
            'km'            => 'max:20',
            'hm'            => 'max:20',
            'date'          => 'required'
        ],[
            'pr_code.required'      => 'Nomor PR wajib diisi!',
            'pr_code.regex'         => 'Nomor PR harus tanpa spasi!'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Validate MR number
//        if(empty($request->input('mr_code')) && empty($request->input('mr_id'))){
//            return redirect()->back()->withErrors('Nomor MR wajib diisi!', 'default')->withInput($request->all());
//        }

        // Validate department
        if($request->input('department') === '-1'){
            return redirect()->back()->withErrors('Pilih departemen!', 'default')->withInput($request->all());
        }

        // Validate priority
//        if($request->input('priority') === '-1'){
//            return redirect()->back()->withErrors('Pilih prioritas!', 'default')->withInput($request->all());
//        }

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

        // Get MR id
        $mrId = $request->input('mr_id');

        // Validate MR relationship
        $validItem = true;
        $validQty = true;
        $i = 0;
        $materialRequest = MaterialRequestHeader::find($mrId);
        foreach($items as $item){
            if(!empty($item)){
                $mrDetail = $materialRequest->material_request_details->where('item_id', $item)->first();
                if(empty($mrDetail)){
                    $validItem = false;
                    break;
                }
                else{
                    if($qtys[$i] > $mrDetail->quantity){
                        $validQty = false;
                        break;
                    }
                }
                $i++;
            }
        }

        if(!$validItem){
            return redirect()->back()->withErrors('Inventory tidak ada dalam MR!', 'default')->withInput($request->all());
        }
        if(!$validQty){
            return redirect()->back()->withErrors('Kuantitas inventory melebihi kuantitas MR!', 'default')->withInput($request->all());
        }

        $user = \Auth::user();
        $now = Carbon::now('Asia/Jakarta');

        $prHeader = PurchaseRequestHeader::create([
            'code'                  => $prCode,
            'material_request_id'   => $mrId,
            'department_id'         => $request->input('department'),
            'priority'              => $request->input('priority'),
            'km'                    => $request->input('km'),
            'hm'                    => $request->input('hm'),
            'status_id'             => 3,
            'created_by'            => $user->id,
            'created_at'            => $now->toDateTimeString()

        ]);

        if($request->filled('machinery')){
            $prHeader->machinery_id = $request->input('machinery');
            $prHeader->save();
        }
        elseif($request->filled('machinery_id')){
            $prHeader->machinery_id = $request->input('machinery_id');
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
                    'header_id'         => $prHeader->id,
                    'item_id'           => $item,
                    'quantity'          => $qty[$idx],
                    'quantity_invoiced' => 0
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
//        if($request->input('priority') === '-1'){
//            return redirect()->back()->withErrors('Pilih prioritas!', 'default')->withInput($request->all());
//        }

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

    /**
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function getIndex(Request $request){
        $purchaseRequests = PurchaseRequestHeader::dateDescending()->get();

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
        $purchase_requests = PurchaseRequestHeader::where('status_id', 3)
            ->where('code', 'LIKE', '%'. $term. '%')
            ->get();

        $formatted_tags = [];

        foreach ($purchase_requests as $purchase_request) {
            $formatted_tags[] = ['id' => $purchase_request->id, 'text' => $purchase_request->code];
        }

        return \Response::json($formatted_tags);
    }

    public function close(Request $request){
        try{
            $user = \Auth::user();
            $now = Carbon::now('Asia/Jakarta');

            $purchaseRequest = PurchaseRequestHeader::find($request->input('id'));
            $purchaseRequest->closed_by = $user->id;
            $purchaseRequest->closed_at = $now->toDateTimeString();
            $purchaseRequest->close_reason = $request->input('reason');
            $purchaseRequest->status_id = 11;
            $purchaseRequest->save();

            Session::flash('message', 'Berhasil tutup PR!');

            return Response::json(array('success' => 'VALID'));
        }
        catch(\Exception $ex){
            return Response::json(array('errors' => 'INVALID'));
        }
    }

    public function report(){
        return View('admin.purchasing.purchase_requests.report');
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

        $tempStart = strtotime($request->input('start_date'));
        $start = date('Y-m-d', $tempStart);
        $tempEnd = strtotime($request->input('end_date'));
        $end = date('Y-m-d', $tempEnd);

        // Validate date
        if($start > $end){
            return redirect()->back()->withErrors('Dari Tanggal tidak boleh lebih besar dari Sampai Tanggal!', 'default')->withInput($request->all());
        }

        $data = PurchaseRequestHeader::whereBetween('date', array($start, $end));

        // Filter status
        $status = $request->input('status');
        if($status != '0'){
            $data = $data->where('status_id', $status);
        }

        $data = $data->orderByDesc('date')
                    ->get();

        // Validate Data
        if(empty($data) || $data->count() == 0){
            return redirect()->back()->withErrors('Data tidak ditemukan!', 'default')->withInput($request->all());
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
}