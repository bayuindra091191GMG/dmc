<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 02/03/2018
 * Time: 14:56
 */

namespace App\Http\Controllers\Admin\Inventory;


use App\Http\Controllers\Controller;
use App\Libs\Utilities;
use App\Models\DeliveryOrderDetail;
use App\Models\DeliveryOrderHeader;
use App\Models\NumberingSystem;
use App\Models\Site;
use App\Transformer\Inventory\DeliveryOrderHeaderTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class DeliveryOrderHeaderController extends Controller
{
    public function index(){
        return View('admin.inventory.delivery_orders.index');
    }

    public function create(){
        $sites = Site::all();

        // Numbering System
        $sysNo = NumberingSystem::where('doc_id', '8')->first();
        $autoNumber = Utilities::GenerateNumber($sysNo->document->code, $sysNo->next_no);

        $data =[
            'sites'          => $sites,
            'autoNumber'    => $autoNumber
        ];

        return View('admin.inventory.delivery_orders.create')->with($data);
    }

    public function show(DeliveryOrderHeader $delivery_order){
        $header = $delivery_order;

        return View('admin.inventory.delivery_orders.show', compact('header'));
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'do_code'           => 'max:30|regex:/^\S*$/u',
            'remark'            => 'max:150',
        ],[
            'do_code.regex'     => 'Nomor Surat Jalan tidak boleh ada spasi'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Validate DO number
        if(empty($request->input('auto_number')) && (empty($request->input('do_code')) || $request->input('do_code') == "")){
            return redirect()->back()->withErrors('Nomor Surat Jalan wajib diisi!', 'default')->withInput($request->all());
        }

        // Validate from & to site
        if($request->input('from_site') === '-1' || $request->input('to_site') === '-1'){
            return redirect()->back()->withErrors('Pilih site keberangkatan & tujuan!', 'default')->withInput($request->all());
        }

        // Generate auto number
        $doCode = 'default';
        if($request->input('auto_number')){
            $sysNo = NumberingSystem::where('doc_id', '8')->first();
            $doCode = Utilities::GenerateNumber($sysNo->document->code, $sysNo->next_no);
            $sysNo->next_no++;
            $sysNo->save();
        }
        else{
            $doCode = $request->input('do_code');
        }

        // Check existing number
        $temp = DeliveryOrderHeader::where('code', $doCode)->first();
        if(!empty($temp)){
            return redirect()->back()->withErrors('Nomor Surat Jalan sudah terpakai!', 'default')->withInput($request->all());
        }

        // Validate details
        $items = $request->input('item');
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

        $user = \Auth::user();
        $now = Carbon::now('Asia/Jakarta');

        $doHeader = DeliveryOrderHeader::create([
            'code'              => $doCode,
            'from_site_id'         => $request->input('from_site'),
            'to_site_id'           => $request->input('to_site'),
            'status_id'         => 3,
            'created_by'        => $user->id,
            'created_at'        => $now->toDateTimeString(),
            'updated_by'        => $user->id
        ]);

        if($request->filled('pr_code')){
            $doHeader->purchase_request_id = $request->input('pr_code');
        }
        else{
            $doHeader->purchase_request_id = $request->input('pr_id');
        }

        if(!empty($request->input('machinery'))){
            $doHeader->machinery_id = $request->input('machinery');
        }

        if($request->filled('remark')){
            $doHeader->remark = $request->input('remark');
        }

        $doHeader->save();

        // Create delivery order detail
        $remarks = $request->input('remark');
        $idx = 0;
        foreach($request->input('item') as $item){
            if(!empty($item)){
                $doDetail = DeliveryOrderDetail::create([
                    'header_id'     => $doHeader->id,
                    'item_id'       => $item,
                    'quantity'      => $qtys[$idx]
                ]);

                if(!empty($remarks[$idx])) {
                    $doDetail->remark = $remarks[$idx];
                    $doDetail->save();
                }
            }
            $idx++;
        }

        Session::flash('message', 'Berhasil membuat surat jalan!');

        return redirect()->route('admin.delivery_orders.show', ['delivery_order' => $doHeader]);
    }

    public function edit(DeliveryOrderHeader $delivery_order){
        $header = $delivery_order;
        $sites = Site::all();

        $data = [
            'header'    => $header,
            'sites'     => $sites
        ];

        return View('admin.inventory.delivery_orders.edit')->with($data);
    }

    public function update(Request $request, DeliveryOrderHeader $delivery_order){
        $validator = Validator::make($request->all(),[
            'remark'        => 'max:150'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = \Auth::user();
        $now = Carbon::now('Asia/Jakarta');

        $delivery_order->remark = $request->input('remark');
        $delivery_order->updated_by = $user->id;
        $delivery_order->updated_at = $now->toDateTimeString();
        $delivery_order->save();

        Session::flash('message', 'Berhasil ubah Surat Jalan!');

        return redirect()->route('admin.delivery_orders.show', ['delivery_order' => $delivery_order]);
    }

    public function getIndex(){
        $deliveryOrders = DeliveryOrderHeader::dateDescending()->get();
        return DataTables::of($deliveryOrders)
            ->setTransformer(new DeliveryOrderHeaderTransformer)
            ->addIndexColumn()
            ->make(true);
    }
}