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
use Maatwebsite\Excel\Facades;
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

    public function download($id){
        $issuedDocket = IssuedDocketHeader::find($id);
        $issuedDocketDetails = IssuedDocketDetail::where('header_id', $issuedDocket->id)->get();

        try {
            $newFileName = $issuedDocket->code.Carbon::now('Asia/Jakarta')->format('Ymdhms');
            $filePath = '/Form Issued Docket.xlsx';

            $path = public_path('documents/');
            Facades\Excel::load($path . $filePath, function($reader) use($issuedDocket, $issuedDocketDetails)
            {
                $reader->sheet('Sheet1', function($sheet) use($issuedDocket, $issuedDocketDetails)
                {
                    //Set The field Data
                    //Header
                    $sheet->getCell('C4')->setValueExplicit(": ".$issuedDocket->date);
                    $sheet->getCell('C5')->setValueExplicit(": ".$issuedDocket->machinery->code);
                    $sheet->getCell('C6')->setValueExplicit(": ".$issuedDocket->department->name);
                    $sheet->getCell('C7')->setValueExplicit(": ".$issuedDocket->division);
                    $sheet->getCell('G4')->setValueExplicit(": ".$issuedDocket->code);
                    $sheet->getCell('G5')->setValueExplicit(": ".$issuedDocket->purchase_request_header->code);

                    //Details
                    $i = 1;
                    $start = 11;
                    foreach ($issuedDocketDetails as $detail){
                        $sheet->getCell('A'.$start)->setValueExplicit($i);
                        $sheet->getCell('B'.$start)->setValueExplicit($detail->time);
                        $sheet->getCell('C'.$start)->setValueExplicit($detail->item->name);
                        $sheet->getCell('D'.$start)->setValueExplicit($detail->item->code);
                        $sheet->getCell('E'.$start)->setValueExplicit($detail->item->uom->description);
                        $sheet->getCell('F'.$start)->setValueExplicit($detail->quantity);
                        $sheet->getCell('G'.$start)->setValueExplicit($detail->remarks);

                        $start++;
                        $i++;
                    }
                });
            })->setFilename($newFileName)->download('xlsx');
        }
        catch (Exception $ex){
            //Utilities::ExceptionLog($ex);
            return response($ex, 500)
                ->header('Content-Type', 'text/plain');
        }
    }

    public function getIndex(){
        $purchaseRequests = IssuedDocketHeader::all();
        return DataTables::of($purchaseRequests)
            ->setTransformer(new IssuedDocketTransformer)
            ->addIndexColumn()
            ->make(true);
    }
}
