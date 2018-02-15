<?php

namespace App\Http\Controllers\Admin\Goods;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\IssuedDocketDetail;
use App\Models\IssuedDocketHeader;
use App\Models\Machinery;
use App\Models\PurchaseRequestHeader;
use App\Transformer\Docket\IssuedDocketTransformer;
use App\Transformer\Purchasing\PurchaseRequestHeaderTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class DocketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View('admin.docket.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $departments = Department::all();

        return view('admin.docket.create', compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'division'      => 'max:90'
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

        $docketHeader = IssuedDocketHeader::create([
            'department_id'     => Input::get('department'),
            'division'          => Input::get('division'),
            'status_id'         => 1,
            'created_by'        => $user->id,
            'updated_by'        => $user->id,
            'created_at'        => $now->toDateString(),
            'date'              => $now->toDateString(),
        ]);

        if(!empty(Input::get('machinery'))){
            $docketHeader->unit_id = Input::get('machinery');
            $docketHeader->save();
        }

        if(!empty(Input::get('purchase_request_header'))){
            $docketHeader->purchase_request_id = Input::get('purchase_request_header');
            $docketHeader->save();
        }

        // Create Issued Docket Detail
        $qty = Input::get('qty');
        $remark = Input::get('remark');
        $time = Input::get('time');
        $idx = 0;
        foreach(Input::get('item') as $item){
            if(!empty($item)){
                $docketDetail = IssuedDocketDetail::create([
                    'header_id'     => $docketHeader->id,
                    'item_id'       => $item,
                    'machinery_id'  => $docketHeader->machinery_id,
                    'time'          => $time[$idx],
                    'quantity'      => $qty[$idx]
                ]);

                if(!empty($remark[$idx])) $docketDetail->remarks = $remark[$idx];
                $docketDetail->save();
            }
            $idx++;
        }

        Session::flash('message', 'Berhasil membuat Issued Docket!');

        return redirect()->route('admin.issued_docket.show', ['issued_docket' => $docketHeader]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(IssuedDocketHeader $issued_docket)
    {
        //
        $header = $issued_docket;

        return View('admin.docket.show', compact('header'));
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

    public function getIndex(){
        $purchaseRequests = IssuedDocketHeader::all();
        return DataTables::of($purchaseRequests)
            ->setTransformer(new IssuedDocketTransformer())
            ->addIndexColumn()
            ->make(true);
    }
}
