<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use App\Libs\Utilities;
use App\Models\Department;
use App\Models\IssuedDocketDetail;
use App\Models\IssuedDocketHeader;
use App\Models\NumberingSystem;
use App\Transformer\Inventory\IssuedDocketTransformer;
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
        return View('admin.inventory.docket.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $departments = Department::all();
        $sysNo = NumberingSystem::where('doc_id', '1')->first();
        $autoNumber = Utilities::GenerateNumber('DOCKET', $sysNo->next_no);

        return view('admin.inventory.docket.create', compact('departments', 'autoNumber'));
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

        //Generate AutoNumber
        if(Input::get('auto_number')) {
            $sysNo = NumberingSystem::where('doc_id', '1')->first();
            $docketNumber = Utilities::GenerateNumber('DOCKET', $sysNo->next_no);
            $sysNo->next_no++;
            $sysNo->save();
        }
        else{
            $docketNumber = Input::get('code');
        }

        $docketHeader = IssuedDocketHeader::create([
            'code'              => $docketNumber,
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

        return redirect()->route('admin.issued_dockets.show', ['issued_docket' => $docketHeader]);
    }

    /**
     * Display the specified resource.
     *
     * @param IssuedDocketHeader $issued_docket
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function show(IssuedDocketHeader $issued_docket)
    {
        //
        $header = $issued_docket;

        return View('admin.inventory.docket.show', compact('header'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     * @internal param IssuedDocketHeader $issuedDocketHeader
     * @internal param PurchaseRequestHeader $purchase_request
     * @internal param int $id
     */
    public function edit($id){
        $header = IssuedDocketHeader::find($id);
        $departments = Department::all();

        return View('admin.inventory.docket.edit', compact('header', 'departments'));
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

        $docketHeader = IssuedDocketHeader::find($id);
        $docketHeader->department_id = Input::get('department');
        $docketHeader->division = Input::get('division');
        $docketHeader->updated_by = $user->id;
        $docketHeader->updated_at = $now->toDateString();

        $docketHeader->save();

        if(!empty(Input::get('machinery'))){
            $docketHeader->unit_id = Input::get('machinery');
            $docketHeader->save();
        }

        if(!empty(Input::get('purchase_request_header'))){
            $docketHeader->purchase_request_id = Input::get('purchase_request_header');
            $docketHeader->save();
        }

        Session::flash('message', 'Berhasil mengubah Issued Docket!');

        return redirect()->route('admin.issued_dockets.edit', ['issued_docket' => $docketHeader->id]);
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

    public function printDocument($id){
        $issuedDocket = IssuedDocketHeader::find($id);
        $issuedDocketDetails = IssuedDocketDetail::where('header_id', $issuedDocket->id)->get();

        return view('documents.issued_dockets.issued_docket', compact('issuedDocket', 'issuedDocketDetails'));
    }

    public function getIndex(){
        $purchaseRequests = IssuedDocketHeader::all();
        return DataTables::of($purchaseRequests)
            ->setTransformer(new IssuedDocketTransformer)
            ->addIndexColumn()
            ->make(true);
    }
}
