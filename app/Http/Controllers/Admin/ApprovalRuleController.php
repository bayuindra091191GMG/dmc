<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApprovalRule;
use App\Models\Auth\User\User;
use App\Models\Document;
use App\Transformer\MasterData\ApprovalRuleTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Session;
use Validator;

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

        Session::flash('message', 'Sukses membuat Pengaturan Approval Baru!');

        return redirect(route('admin.approval_rules.create'));
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
    public function edit($id)
    {
        //
        $approvalRule = ApprovalRule::find($id);
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

    public function getIndex()
    {
        $approvalRules = ApprovalRule::all();
        return DataTables::of($approvalRules)
            ->setTransformer(new ApprovalRuleTransformer())
            ->addIndexColumn()
            ->make(true);
    }
}
