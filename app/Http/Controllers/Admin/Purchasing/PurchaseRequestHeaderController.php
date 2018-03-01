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
use App\Models\NumberingSystem;
use App\Models\PurchaseRequestDetail;
use App\Models\PurchaseRequestHeader;
use App\Transformer\Purchasing\PurchaseRequestHeaderTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

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

        return View('admin.purchasing.purchase_requests.show', compact('header'));
    }

    public function store(Request $request){

        $validator = Validator::make($request->all(),[
            'pr_code'       => 'max:40',
            'sn_chasis'     => 'max:90',
            'sn_engine'     => 'max:90',
            'priority'      => 'max:40',
            'km'            => 'max:40',
            'hm'            => 'max:40'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Validate PR number
        if(empty(Input::get('auto_number')) && (empty(Input::get('pr_code'))) || Input::get('pr_code') == ""){
            return redirect()->back()->withErrors('Nomor PR wajib diisi!', 'default')->withInput($request->all());
        }

        // Validate department
        if(Input::get('department') === '-1'){
            return redirect()->back()->withErrors('Pilih departemen!', 'default')->withInput($request->all());
        }

        // Generate auto number
        $prCode = 'default';
        if(Input::get('auto_number')){
            $sysNo = NumberingSystem::where('doc_id', '3')->first();
            $prCode = Utilities::GenerateNumber('PR', $sysNo->next_no);
            $sysNo->next_no++;
            $sysNo->save();
        }
        else{
            $prCode = Input::get('po_code');
        }

        // Check existing number
        $temp = PurchaseRequestHeader::where('code', $prCode)->first();
        if(!empty($temp)){
            return redirect()->back()->withErrors('Nomor PR sudah terdaftar!', 'default')->withInput($request->all());
        }

        $user = \Auth::user();
        $now = Carbon::now('Asia/Jakarta');

        $prHeader = PurchaseRequestHeader::create([
            'code'              => $prCode,
            'department_id'     => Input::get('department'),
            'sn_chasis'         => Input::get('sn_chasis'),
            'sn_engine'         => Input::get('sn_engine'),
            'priority'          => Input::get('priority'),
            'km'                => Input::get('km'),
            'hm'                => Input::get('hm'),
            'status_id'         => 3,
            'created_by'        => $user->id,
            'created_at'        => $now->toDateTimeString()

        ]);

        if(!empty(Input::get('machinery'))){
            $prHeader->machinery_id = Input::get('machinery');
            $prHeader->save();
        }

        // Create purchase request detail
        $qty = Input::get('qty');
        $remark = Input::get('remark');
        $idx = 0;
        foreach(Input::get('item') as $item){
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

        $data = [
            'header'        => $header,
            'departments'   => $departments
        ];

        return View('admin.purchasing.purchase_requests.edit')->with($data);
    }

    public function getIndex(){
        $purchaseRequests = PurchaseRequestHeader::all();
        return DataTables::of($purchaseRequests)
            ->setTransformer(new PurchaseRequestHeaderTransformer)
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