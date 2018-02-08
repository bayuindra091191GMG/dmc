<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 07/02/2018
 * Time: 10:22
 */

namespace App\Http\Controllers\Admin\Purchasing;


use App\Http\Controllers\Controller;
use App\Models\Department;
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

        return View('admin.purchasing.purchase_requests.create', compact('departments'));
    }

    public function show(PurchaseRequestHeader $purchase_request){
        $header = $purchase_request;

//        dd($header->department->name);

        return View('admin.purchasing.purchase_requests.show', compact('header'));
    }

    public function store(Request $request){

        $validator = Validator::make($request->all(),[
            'sn_chasis'      => 'max:90',
            'sn_engine'     => 'max:90'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        if(Input::get('department') === '-1'){
            return redirect()->back()->withErrors('Pilih departemen!', 'default')->withInput($request->all());
        }

        $user = \Auth::user();
        $now = Carbon::now('Asia/Jakarta');

        $prHeader = PurchaseRequestHeader::create([
            'department_id'     => Input::get('department'),
            'sn_chasis'         => Input::get('sn_chasis'),
            'sn_engine'         => Input::get('sn_engine'),
            'status_id'         => 1,
            'created_by'        => $user->id,
            'created_at'        => $now->toDateString()

        ]);

        if(!empty(Input::get('machinery'))){
            $prHeader->machinery_id = Input::get('machinery');
            $prHeader->save();
        }

        // Create purchase request detail
        $qty = Input::get('qty');
        $remark = Input::get('remark');
        $deliveryDate = Input::get('date');
        $idx = 0;
        foreach(Input::get('item') as $item){
            $prDetail = PurchaseRequestDetail::create([
                'header_id'     => $prHeader->id,
                'item_id'       => $item,
                'quantity'      => $qty[$idx]
            ]);

            if(!empty($remark[$idx])) $prDetail->remark = $remark[$idx];
            if(!empty($deliveryDate[$idx])){
                $date = Carbon::createFromFormat('d M Y', $deliveryDate[$idx], 'Asia/Jakarta');
                $prDetail->delivery_date = $date->toDateString();
            }
            $prDetail->save();
            $idx++;
        }

        Session::flash('message', 'Berhasil membuat purchase request!');

        return redirect()->route('admin.purchasing.purchase_requests.show', ['purchase_request_header' => $prHeader]);
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
}