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
use App\Mail\ApprovalPurchaseRequestCreated;
use App\Models\ApprovalPurchaseRequest;
use App\Models\ApprovalRule;
use App\Models\Department;
use App\Models\MaterialRequestHeader;
use App\Models\NumberingSystem;
use App\Models\PreferenceCompany;
use App\Models\PurchaseOrderHeader;
use App\Models\PurchaseRequestDetail;
use App\Models\PurchaseRequestHeader;
use App\Transformer\Purchasing\PurchaseRequestHeaderTransformer;
use Carbon\Carbon;
use Hamcrest\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use PDF;
use Yajra\DataTables\DataTables;

class PurchaseRequestHeaderController extends Controller
{
    public function index(Request $request){

        $filterStatus = '3';
        if($request->status != null){
            $filterStatus = $request->status;
        }

        return View('admin.purchasing.purchase_requests.index', compact('filterStatus'));
    }

    public function beforeCreate(){
        return View('admin.purchasing.purchase_requests.before_create');
    }

    public function create(){

        if(empty(request()->mr)){
            return redirect()->route('admin.purchase_requests.before_create');
        }

        $mrId = request()->mr;

        // Validate PR exists
        if(PurchaseRequestHeader::where('material_request_id', $mrId)->exists()){
            return redirect()->route('admin.purchase_requests.before_create');
        }

        $materialRequest = MaterialRequestHeader::find($mrId);
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

//        $prShowRoute = route('admin.purchase_requests.show', ['purchase_request' => $header->id]);
//
//        $data =[
//            'purchase_request'      => $header,
//            'user'                  => Auth::user(),
//            'url'                   => route('login', ['redirect' => $prShowRoute])
//        ];
//
//        return View('email.approval_purchase_request')->with($data);

        $date = Carbon::parse($purchase_request->date)->format('d M Y');
        $priorityLimitDate = Carbon::parse($purchase_request->priority_limit_date)->format('d M Y');

        //Check Approval & Permission to Print
        $user = \Auth::user();
        $permission = true;
        $isUserMustApprove = false;
        //Kondisi belum diapprove
        $status = 0;
        $arrData = array();

        // Check Approval Feature
        $preference = PreferenceCompany::find(1);
        $approvals = null;

        if($preference->approval_setting == 1) {
            $tempApprove = ApprovalRule::where('document_id', 3)->where('user_id', $user->id)->get();
            $approvals = ApprovalRule::where('document_id', 3)->get();
            $approvalPr = ApprovalPurchaseRequest::where('purchase_request_id', $purchase_request->id)->get();

            if ($tempApprove->count() > 0) {
                $isUserMustApprove = true;
            }

            $approvalData = ApprovalPurchaseRequest::where('purchase_request_id', $header->id)->where('user_id', $user->id)->first();
            if(!empty($approvalData)){
                $isUserMustApprove = false;
            }

            //Kondisi Approve Sebagian
            $approvalPrData = ApprovalPurchaseRequest::where('purchase_request_id', $header->id)->get();
            if($approvalData != null || $approvalPrData != null){
                $status = $approvalPrData->count();

                //Kondisi Semua sudah Approve
                if($approvalPrData->count() == $approvals->count()){
                    $status = 99;
                }
            }

            if ($approvals->count() != $approvalPr->count()) {
                $permission = false;
            }

            foreach($approvals as $approval)
            {
                $flag = 0;
                foreach($approvalPr as $data)
                {
                    if($data->user_id == $approval->user_id)
                    {
                        $flag = 1;
                    }
                }

                if($flag == 1){
                    $arrData[] = $approval->user->name . " - Sudah Approve";
                }
                else{
                    $arrData[] = $approval->user->name . " - Belum Approve";
                }
            }
        }

        // Check PO created
        $isPoCreated = false;
        if(PurchaseOrderHeader::where('purchase_request_id', $header->id)->exists()){
            $isPoCreated = true;
        }

        $data = [
            'header'            => $header,
            'date'              => $date,
            'priorityLimitDate' => $priorityLimitDate,
            'permission'        => $permission,
            'approveOrder'      => $isUserMustApprove,
            'status'            => $status,
            'approvalData'      => $arrData,
            'setting'           => $preference->approval_setting,
            'isPoCreated'       => $isPoCreated
        ];
        //dd($status);
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

        // Generate auto number
        $prCode = 'default';
        if($request->filled('auto_number')){
            $sysNo = NumberingSystem::where('doc_id', '3')->first();
            $prCode = Utilities::GenerateNumber($sysNo->document->code, $sysNo->next_no);

            // Check existing number
            if(PurchaseOrderHeader::where('code', $prCode)->exists()){
                return redirect()->back()->withErrors('Nomor PR sudah terdaftar!', 'default')->withInput($request->all());
            }

            $sysNo->next_no++;
            $sysNo->save();
        }
        else{
            $prCode = $request->input('pr_code');

            // Check existing number
            if(PurchaseOrderHeader::where('code', $prCode)->exists()){
                return redirect()->back()->withErrors('Nomor PR sudah terdaftar!', 'default')->withInput($request->all());
            }
        }

        $user = \Auth::user();
        $now = Carbon::now('Asia/Jakarta');
        $date = Carbon::createFromFormat('d M Y', $request->input('date'), 'Asia/Jakarta');
        $limitDate = Carbon::createFromFormat('d M Y', $request->input('date'), 'Asia/Jakarta');

        $priority = $request->input('priority');
        if($priority == '1'){
            $limitDate->addDays(8);
        }
        elseif($priority == '2'){
            $limitDate->addDays(15);
        }
        else{
            $limitDate->addDays(22);
        }

        $prHeader = PurchaseRequestHeader::create([
            'code'                  => $prCode,
            'material_request_id'   => $mrId,
            'date'                  => $date->toDateTimeString(),
            'department_id'         => $request->input('department'),
            'priority'              => $request->input('priority'),
            'priority_limit_date'   => $limitDate->toDateTimeString(),
            'km'                    => $request->input('km'),
            'hm'                    => $request->input('hm'),
            'status_id'             => 3,
            'created_by'            => $user->id,
            'created_at'            => $now->toDateTimeString()
        ]);

        if($request->filled('machinery_id')){
            $prHeader->machinery_id = $request->input('machinery_id');
            $prHeader->save();
        }

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

        // Check Approval Feature
        $preference = PreferenceCompany::find(1);

        try{
            if($preference->approval_setting == 1) {
                $approvals = ApprovalRule::where('document_id', 3)->get();
                if($approvals->count() > 0){
                    foreach($approvals as $approval){
                        if(!empty($approval->user->email_address)){
                            Mail::to($approval->user->email_address)->send(new ApprovalPurchaseRequestCreated($prHeader, $approval->user));
                        }
                    }
                }
            }
        }
        catch (\Exception $ex){
            dd($ex);
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

        $user = \Auth::user();
        $now = Carbon::now('Asia/Jakarta');
        $date = Carbon::createFromFormat('d M Y', $request->input('date'), 'Asia/Jakarta');
        $limitDate = Carbon::createFromFormat('d M Y', $request->input('date'), 'Asia/Jakarta');

        if($purchase_request->priority == '1'){
            $limitDate->addDays(8);
        }
        elseif($purchase_request->priority== '2'){
            $limitDate->addDays(15);
        }
        else{
            $limitDate->addDays(22);
        }

        $purchase_request->department_id = $request->input('department');
        $purchase_request->priority_limit_date = $limitDate->toDateTimeString();
        $purchase_request->date = $date;
        $purchase_request->updated_by = $user->id;
        $purchase_request->updated_at = $now->toDateTimeString();
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

        $mode = 'default';
        if($request->filled('mode')){
            $mode = $request->input('mode');
        }

        $status = '0';
        if($request->filled('status')){
            $status = $request->input('status');
            if($status != '0'){
                $purchaseRequests = PurchaseRequestHeader::where('status_id', $status)
                    ->dateDescending()
                    ->get();
            }
            else{
                $purchaseRequests = PurchaseRequestHeader::dateDescending()->get();
            }
        }
        else{
            $purchaseRequests = PurchaseRequestHeader::dateDescending()->get();
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
        $departments = Department::all();

        return View('admin.purchasing.purchase_requests.report', compact('departments'));
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

        $data = PurchaseRequestHeader::whereBetween('date', array($start->toDateTimeString(), $end->toDateTimeString()));

        // Filter departemen
        $department = $request->input('department');
        if($department != '0'){
            $data = $data->where('department_id', $department);
        }

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
        $pdf->setOptions(["isPhpEnabled"=>true]);

        return $pdf->download($filename.'.pdf');
    }

    public function download($id){
        $purchaseRequest = PurchaseRequestHeader::find($id);
        $purchaseRequestDetails = PurchaseRequestDetail::where('header_id', $purchaseRequest->id)->get();

        $pdf = PDF::loadView('documents.purchase_requests.purchase_requests_doc', ['purchaseRequest' => $purchaseRequest, 'purchaseRequestDetails' => $purchaseRequestDetails]);
        $now = Carbon::now('Asia/Jakarta');
        $filename = $purchaseRequest->code. '_' . $now->toDateTimeString();
        $pdf->set_option("isPhpEnabled", true);

        return $pdf->stream($filename.'.pdf');
    }

    public function printDocument($id){
        $purchaseRequest = PurchaseRequestHeader::find($id);
        $purchaseRequestDetails = PurchaseRequestDetail::where('header_id', $purchaseRequest->id)->get();
        $approvalUser = ApprovalRule::where('document_id', 3)->get();
        $temp = PreferenceCompany::find(1);
        $setting = $temp->approval_setting;

        return view('documents.purchase_requests.purchase_requests_doc', compact('purchaseRequest', 'purchaseRequestDetails', 'approvalUser', 'setting'));
    }
}