<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use App\Libs\Utilities;
use App\Models\Department;
use App\Models\Document;
use App\Models\IssuedDocketDetail;
use App\Models\IssuedDocketHeader;
use App\Models\Item;
use App\Models\ItemStock;
use App\Models\MaterialRequestDetail;
use App\Models\MaterialRequestHeader;
use App\Models\NumberingSystem;
use App\Models\PurchaseRequestDetail;
use App\Models\PurchaseRequestHeader;
use App\Models\StockCard;
use App\Models\Warehouse;
use App\Transformer\Inventory\IssuedDocketTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades;
use Yajra\DataTables\DataTables;
use PDF;

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

    public function beforeCreate(){
        return View('admin.inventory.docket.before_create');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $materialRequest = null;
        if(!empty(request()->mr)){
            $materialRequest = MaterialRequestHeader::find(request()->mr);
        }

        $sysNo = NumberingSystem::where('doc_id', '1')->first();
        $document = Document::where('id', '1')->first();
        $autoNumber = Utilities::GenerateNumber($document->code, $sysNo->next_no);
        $warehouse = Warehouse::where('id', '!=', 0)->get();

        return view('admin.inventory.docket.create', compact('departments', 'autoNumber', 'materialRequest', 'warehouse'));
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
            'code'          => 'max:40'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = \Auth::user();
        $now = Carbon::now('Asia/Jakarta');

        //Generate AutoNumber
        if(Input::get('auto_number')) {
            $sysNo = NumberingSystem::where('doc_id', '1')->first();
            $document = Document::where('id', '1')->first();
            $docketNumber = Utilities::GenerateNumber($document->code, $sysNo->next_no);
            $sysNo->next_no++;
            $sysNo->save();
        }
        else{
            if(empty(Input::get('code'))){
                return redirect()->back()->withErrors('No Issued Docket Wajib Diisi!', 'default')->withInput($request->all());
            }
            $docketNumber = Input::get('code');
        }

        // Validate details
        $mrId = Input::get('mr_id');
        $items = Input::get('item_value');
        $qtys = Input::get('qty');
        $valid = true;
        $mrCheck = true;
        $qtyCheck = true;
        $wrCheck = true;
        $wrQtyCheck = true;
        $i = 0;
        $materialRequest = MaterialRequestHeader::where('code', $mrId)->first();

        foreach($items as $item){
            if(empty($item)) $valid = false;
            if(empty($qtys[$i]) || $qtys[$i] == '0') $valid = false;

            //Validate Details with PR Data
            if($materialRequest->material_request_details[$i]->quantity < $qtys[$i]){
                $mrCheck = false;
            }

            //Check Item in Stock
            $tempItem = ItemStock::where('item_id', $item)
                ->where('warehouse_id', Input::get('warehouse'))
                ->first();

            if($tempItem == null){
                $wrCheck = false;
            }
            else if($tempItem->stock == null || $tempItem->stock == 0){
                $wrQtyCheck = false;
            }
            else {
                $lastStock = $tempItem->stock - $qtys[$i];
                if ($tempItem->stock < $qtys[$i] || $lastStock < 0) {
                    $qtyCheck = false;
                }
            }

            $i++;
        }

        if(!$valid){
            return redirect()->back()->withErrors('Kuantitas wajib diisi!', 'default')->withInput($request->all());
        }
        if(!$mrCheck){
            return redirect()->back()->withErrors('Kuantitas tidak boleh melebihi kuantitas di MR!', 'default')->withInput($request->all());
        }
        if(!$qtyCheck){
            return redirect()->back()->withErrors('Stock tidak mencukupi!', 'default')->withInput($request->all());
        }
        if(!$wrCheck){
            return redirect()->back()->withErrors('Inventory tidak ada di gudang yang dipilih!', 'default')->withInput($request->all());
        }
        if(!$wrQtyCheck){
            return redirect()->back()->withErrors('Stock tidak ada pada gudang yang dipilih!', 'default')->withInput($request->all());
        }

        // Check duplicate inventory
        $valid = Utilities::arrayIsUnique($items);
        if(!$valid){
            return redirect()->back()->withErrors('Detail inventory tidak boleh kembar!', 'default')->withInput($request->all());
        }

        $docketHeader = IssuedDocketHeader::create([
            'code'                          => $docketNumber,
            'department_id'                 => $materialRequest->department_id,
            'unit_id'                       => $materialRequest->machinery_id,
            'division'                      => Input::get('division'),
            'warehouse_id'                  => Input::get('warehouse'),
            'status_id'                     => 1,
            'hm'                            => $materialRequest->hm,
            'km'                            => $materialRequest->km,
            'created_by'                    => $user->id,
            'updated_by'                    => $user->id,
            'created_at'                    => $now->toDateString(),
            'date'                          => $now->toDateString(),
            'material_request_header_id'    => $materialRequest->id
        ]);

        $docketHeader->save();

        // Create Issued Docket Detail
        $remark = Input::get('remark');
        $idx = 0;

        foreach($items as $item){
            if(!empty($item)){
                $qty = (int) $qtys[$idx];

                $docketDetail = IssuedDocketDetail::create([
                    'header_id'     => $docketHeader->id,
                    'item_id'       => $item,
                    'machinery_id'  => $docketHeader->machinery_id,
                    'quantity'      => $qty
                ]);

                if(!empty($remark[$idx])) $docketDetail->remarks = $remark[$idx];
                $docketDetail->save();

                //Update Stock
                //Item Stock
                $itemStockData = ItemStock::where('item_id', $docketDetail->item_id)
                        ->where('warehouse_id', $docketHeader->warehouse_id)
                        ->first();
                $itemStockData->stock = $itemStockData->stock - $qty;
                $itemStockData->save();

                //Stock Card
                StockCard::create([
                    'item_id'       => $item,
                    'change'        => $qty[$idx],
                    'stock'         => $itemStockData->stock,
                    'warehouse_id'  => $request->input('warehouse'),
                    'created_by'    => $user->id,
                    'created_at'    => $now,
                    'flag'          => '-',
                    'description'   => 'Issued Docket ' . $docketHeader->code
                ]);

                //item
                $itemData = Item::where('id', $item)->first();
                $itemData->stock = $itemData->stock - $qty;
                $itemData->save();

                //Update Material Request Detail
                $mrDetail = MaterialRequestDetail::find($materialRequest->material_request_details[$idx]->id);
                $mrDetail->quantity_issued += $qty;
                $mrDetail->save();
            }
            $idx++;
        }

        //Update Material Request Header
        $materialRequest = MaterialRequestHeader::where('code', $mrId)->first();
        $done = true;
        foreach ($materialRequest->material_request_details as $detail){
            if($detail->quantity != $detail->quantity_issued){
                $done = false;
            }
        }
        if($done){
            $materialRequest->status_id = 4;
            $materialRequest->save();
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

    public function downloadExcel($id){
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
            })->setFilename($newFileName)->export('xlsx');
        }
        catch (Exception $ex){
            //Utilities::ExceptionLog($ex);
            return response($ex, 500)
                ->header('Content-Type', 'text/plain');
        }
    }

    public function report(){
        return View('admin.inventory.docket.report');
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

        //Check date
        if($start > $end){
            return redirect()->back()->withErrors('Dari Tanggal tidak boleh lebih besar dari Sampai Tanggal!', 'default')->withInput($request->all());
        }

        $data = IssuedDocketHeader::whereBetween('date', array($start, $end))->get();

        //Check Data
        if($data == null || $data->count() == 0){
            return redirect()->back()->withErrors('Data tidak ditemukan!', 'default')->withInput($request->all());
        }

        $pdf = PDF::loadView('documents.issued_dockets.issued_docket_pdf', ['data' => $data, 'start_date' => Input::get('start_date'), 'finish_date' => Input::get('end_date')])
            ->setPaper('a4', 'landscape');
        $now = Carbon::now('Asia/Jakarta');
        $filename = 'ISSUED_DOCKET_REPORT_' . $now->toDateTimeString();

        return $pdf->download($filename.'.pdf');
    }

    public function download($id){
        $issuedDocket = IssuedDocketHeader::find($id);
        $issuedDocketDetails = IssuedDocketDetail::where('header_id', $issuedDocket->id)->get();

        $pdf = PDF::loadView('documents.issued_dockets.issued_docket_doc', ['issuedDocket' => $issuedDocket, 'issuedDocketDetails' => $issuedDocketDetails])->setPaper('A4');
        $now = Carbon::now('Asia/Jakarta');
        $filename = $issuedDocket->code. '_' . $now->toDateTimeString();

        return $pdf->download($filename.'.pdf');
    }

    public function getIndex(){
        $purchaseRequests = IssuedDocketHeader::all();
        return DataTables::of($purchaseRequests)
            ->setTransformer(new IssuedDocketTransformer)
            ->addIndexColumn()
            ->make(true);
    }
}
