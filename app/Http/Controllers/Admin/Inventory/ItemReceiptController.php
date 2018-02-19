<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use App\Models\DeliveryNoteHeader;
use App\Models\ItemReceiptDetail;
use App\Models\ItemReceiptHeader;
use App\Models\PurchaseOrderHeader;
use App\Transformer\Inventory\ItemReceiptTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Input;

class ItemReceiptController extends Controller
{
    //
    public function index(){
        return View('admin.inventory.item_receipts.index');
    }

    public function create(){
        $deliveries = DeliveryNoteHeader::all();
        $purchaseOrders = PurchaseOrderHeader::all();

        return View('admin.inventory.item_receipts.create', compact('deliveries', 'purchaseOrders'));
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'date'          => 'required',
            'no_sj_spb'     => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        //Galau
        if(Input::get('department') === '-1'){
            return redirect()->back()->withErrors('Pilih departemen!', 'default')->withInput($request->all());
        }

        $user = \Auth::user();
        $now = Carbon::now('Asia/Jakarta');

        //Generate AutoNumber
        $sysNo = NumberingSystem::where('doc_id', '2')->first();
        $docketNumber = Utilities::GenerateNumber('BTB', $sysNo->next_no);
        $sysNo->next_no++;
        $sysNo->save();

        $docketHeader = ItemReceiptHeader::create([
            'code'              => $docketNumber,
            'no_sj_spb'         => Input::get('department'),
            'date'              => $now->toDateString(),
            'remarks'           => 1,
            'delivery_note_id'  => 1,
            'status_id'         => 1,
            'created_by'        => $user->id,
            'updated_by'        => $user->id,
            'created_at'        => $now->toDateString(),
        ]);

        // Create Item Receipt Detail
        $qty = Input::get('qty');
        $remark = Input::get('remark');
        $purchaseOrderId = Input::get('time');
        $idx = 0;
        foreach(Input::get('item') as $item){
            if(!empty($item)){
                $docketDetail = ItemReceiptDetail::create([
                    'header_id'         => $docketHeader->id,
                    'item_id'           => $item,
                    'purchase_order_id' => $purchaseOrderId[$idx],
                    'remark'            => $remark[$idx],
                    'quantity'          => $qty[$idx]
                ]);

                if(!empty($remark[$idx])) $docketDetail->remarks = $remark[$idx];
                $docketDetail->save();
            }
            $idx++;
        }

        Session::flash('message', 'Berhasil membuat Item Receipt!');

        return redirect()->route('admin.item_receipts.show', ['item_receipts' => $docketHeader]);
    }

    public function edit(){

    }

    public function update(){

    }

    public function delete(){

    }

    public function print(){

    }

    public function getIndex(){
        $purchaseRequests = ItemReceiptHeader::all();
        return DataTables::of($purchaseRequests)
            ->setTransformer(new ItemReceiptTransformer())
            ->addIndexColumn()
            ->make(true);
    }
}
