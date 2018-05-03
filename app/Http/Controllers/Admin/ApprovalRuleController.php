<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApprovalPaymentRequest;
use App\Models\ApprovalPurchaseOrder;
use App\Models\ApprovalPurchaseRequest;
use App\Models\ApprovalRule;
use App\Models\Auth\User\User;
use App\Models\Document;
use App\Models\PurchaseOrderHeader;
use App\Models\PurchaseRequestHeader;
use App\Transformer\MasterData\ApprovalRuleTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Session;

class ApprovalRuleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('admin.approval_rules.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $users = User::all();
        $documents = Document::all();

        return view('admin.approval_rules.create', compact('users', 'documents'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'user' => 'required',
            'document' => 'required',
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());

        //Checking
        if(Input::get('user') === '-1'){
            return redirect()->back()->withErrors('Pilih user!', 'default')->withInput($request->all());
        }
        if(Input::get('document') === '-1'){
            return redirect()->back()->withErrors('Pilih dokumen!', 'default')->withInput($request->all());
        }

        $user_id = $request->get('user');
        $document_id = $request->get('document');

        $rule = ApprovalRule::where('user_id', $user_id)->where('document_id', $document_id)->first();
        if($rule != null){
            Session::flash('error', 'Pengaturan Approval Sudah Dibuat!');
            return redirect(route('admin.approval_rules.create'));
        }

        $dateTimeNow = Carbon::now('Asia/Jakarta');
        $user = Auth::user();

        ApprovalRule::create([
            'user_id'           => $user_id,
            'document_id'       => $document_id,
            'created_by'        => $user->id,
            'created_at'        => $dateTimeNow->toDateTimeString(),
            'updated_by'        => $user->id,
            'updated_at'        => $dateTimeNow->toDateTimeString()
        ]);

        Session::flash('message', 'Berhasil membuat data pengaturan approval baru!');

        return redirect(route('admin.approval_rules'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(ApprovalRule $approvalRule)
    {
        $users = User::all();
        $documents = Document::all();

        return view('admin.approval_rules.edit', compact('approvalRule', 'users', 'documents'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $validator = Validator::make($request->all(), [
            'user' => 'required',
            'document' => 'required',
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());
        $dateTimeNow = Carbon::now('Asia/Jakarta');
        $user = Auth::user();
        $user_id = $request->get('user');
        $document_id = $request->get('document');

        //Check
        $check = ApprovalRule::where('user_id', $user_id)->where('document_id', $document_id)->first();
        if($check != null){
            Session::flash('error', 'Pengaturan Approval Sudah Dibuat!');
            return redirect(route('admin.approval_rules.create'));
        }

        $rule = ApprovalRule::find($id);
        $rule->user_id = $user_id;
        $rule->document_id = $document_id;
        $rule->updated_by = $user->id;
        $rule->updated_at = $dateTimeNow->toDateTimeString();
        $rule->save();

        Session::flash('message', 'Sukses mengubah data Pengaturan Approval!');

        return redirect(route('admin.approval_rules.edit', ['approval_rules' => $rule->id]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    //Purchase Request Approval
    public function prApproval($approval_rule){
        $user = Auth::user();
        $header = PurchaseRequestHeader::find($approval_rule);
        $date = Carbon::parse($header->date)->format('d M Y');
        $priorityLimitDate = Carbon::parse($header->priority_limit_date)->format('d M Y');

        //Check kondisi Approval
        $approvals = ApprovalRule::where('document_id', 3)->get();
        $approvalData = ApprovalPurchaseRequest::where('purchase_request_id', $header->id)->where('user_id', $user->id)->first();
        $status = 0;
        $approvalPrData = ApprovalPurchaseRequest::where('purchase_request_id', $header->id)->get();
        if($approvalData != null || $approvalPrData != null){
            $status = $approvalPrData->count();

            //Kondisi Semua sudah Approve
            if($approvalPrData->count() == $approvals->count()){
                $status = 99;
            }
        }

        $approvalRule = ApprovalRule::where('document_id', '3')->get();

        $data = [
            'header'            => $header,
            'date'              => $date,
            'priorityLimitDate' => $priorityLimitDate,
            'status'            => $status,
            'approvalData'      => $approvalRule
        ];

        return View('admin.approval_rules.approval_pr')->with($data);
    }

    public function approvePr($id){
        $datas = ApprovalRule::where('document_id', 3)->get();
        $count = $datas->count();

        //Create Approval
        $dateTimeNow = Carbon::now('Asia/Jakarta');
        $user = Auth::user();

        $valid = false;
        $exist = true;
        $approvalData = ApprovalPurchaseRequest::where('purchase_request_id', $id)->where('user_id', $user->id)->first();
        if($approvalData != null){
            $exist = false;
        }

        foreach ($datas as $data){
            if($user->id == $data->user_id){
                $valid = true;
            }
        }

        if(!$valid){
            return redirect()->back()->withErrors('Anda tidak berhak melakukan approval dokumen ini!', 'default');
        }
        if(!$exist){
            return redirect()->back()->withErrors('Anda sudah melakukan approval dokumen ini!', 'default');
        }

        ApprovalPurchaseRequest::create([
            'purchase_request_id'   => $id,
            'user_id'               => $user->id,
            'created_at'            => $dateTimeNow->toDateTimeString(),
            'updated_at'            => $dateTimeNow->toDateTimeString(),
            'created_by'            => $user->id,
            'updated_by'            => $user->id
        ]);

        //Update Document Status
        $approvalCount = ApprovalPurchaseRequest::where('purchase_request_id', $id)->get()->count();
        if($approvalCount == $count){
            $purchaseRequest = PurchaseRequestHeader::find($id);
            $purchaseRequest->is_approved = 1;
        }

        Session::flash('message', 'Berhasil Approve Dokumen ini!');

        return redirect()->route('admin.approval_rules.pr_approval', ['approval_rule' => $id]);
    }

    //Purchase Request Approval
    public function poApproval($approval_rule){
        $user = Auth::user();
        $header = PurchaseOrderHeader::find($approval_rule);
        $date = Carbon::parse($header->date)->format('d M Y');
        $priorityLimitDate = Carbon::parse($header->priority_limit_date)->format('d M Y');

        //Check kondisi Approval
        $approvals = ApprovalRule::where('document_id', 4)->get();
        $approvalData = ApprovalPurchaseOrder::where('purchase_order_id', $header->id)->where('user_id', $user->id)->first();
        $status = 0;
        $approvalPoData = ApprovalPurchaseOrder::where('purchase_order_id', $header->id)->get();
        if($approvalData != null || $approvalPoData != null){
            $status = $approvalPoData->count();

            //Kondisi Semua sudah Approve
            if($approvalPoData->count() == $approvals->count()){
                $status = 99;
            }
        }

        $approvalRule = ApprovalRule::where('document_id', '4')->get();

        $data = [
            'header'            => $header,
            'date'              => $date,
            'priorityLimitDate' => $priorityLimitDate,
            'status'            => $status,
            'approvalData'      => $approvalRule
        ];

        return View('admin.approval_rules.approval_po')->with($data);
    }

    public function approvePo($id){
        $datas = ApprovalRule::where('document_id', 4)->get();
        $count = $datas->count();

        //Create Approval
        $dateTimeNow = Carbon::now('Asia/Jakarta');
        $user = Auth::user();

        $valid = false;
        $exist = true;
        $approvalData = ApprovalPurchaseOrder::where('purchase_order_id', $id)->where('user_id', $user->id)->first();
        if($approvalData != null){
            $exist = false;
        }

        foreach ($datas as $data){
            if($user->id == $data->user_id){
                $valid = true;
            }
        }

        if(!$valid){
            return redirect()->back()->withErrors('Anda tidak berhak melakukan approval dokumen ini!', 'default');
        }
        if(!$exist){
            return redirect()->back()->withErrors('Anda sudah melakukan approval dokumen ini!', 'default');
        }

        ApprovalPurchaseOrder::create([
            'purchase_order_id'   => $id,
            'user_id'             => $user->id,
            'created_at'          => $dateTimeNow->toDateTimeString(),
            'updated_at'          => $dateTimeNow->toDateTimeString(),
            'created_by'          => $user->id,
            'updated_by'          => $user->id
        ]);

        //Update Document Status
        $approvalCount = ApprovalPurchaseOrder::where('purchase_order_id', $id)->get()->count();
        if($approvalCount == $count){
            $purchaseOrder = PurchaseOrderHeader::find($id);
            $purchaseOrder->is_approved = 1;
        }

        Session::flash('message', 'Berhasil Approve Dokumen ini!');

        return redirect()->route('admin.approval_rules.po_approval', ['approval_rule' => $id]);
    }

    public function getIndex()
    {
        $approvalRules = ApprovalRule::all();
        return DataTables::of($approvalRules)
            ->setTransformer(new ApprovalRuleTransformer())
            ->addIndexColumn()
            ->make(true);
    }
}

