<?php
/**
 * Created by PhpStorm.
 * User: hellb
 * Date: 2/18/2018
 * Time: 4:45 PM
 */

namespace App\Http\Controllers\Admin\Purchasing;


use App\Http\Controllers\Controller;
use App\Models\PurchaseOrderDetail;
use App\Models\PurchaseOrderHeader;
use App\Models\PurchaseRequestHeader;
use App\Transformer\Purchasing\PurchaseOrderHeaderTransformer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;

class PurchaseOrderHeaderController extends Controller
{
    public function index(){

        return View('admin.purchasing.purchase_orders.index');
    }

    public function create(){

        $purchaseRequest = null;
        if(!empty(request()->pr)){
            $purchaseRequest = PurchaseRequestHeader::find(request()->pr);
        }

        $quotation = null;

        $data = [
            'purchaseRequest'   => $purchaseRequest,
            'quotation'         => $quotation
        ];

        return View('admin.purchasing.purchase_orders.create')->with($data);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'po_code'       => 'required',
            'pr_code'       => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check detail
        $items = Input::get('item_value');
        $valid = false;
        foreach($items as $item){
            if(!empty($item)) $valid = true;
        }

        if(!$valid){
            return redirect()->back()->withErrors('Daftar barang wajib diisi!', 'default')->withInput($request->all());
        }

        $user = \Auth::user();
        $now = Carbon::now('Asia/Jakarta');

        $poHeader = PurchaseOrderHeader::create([
            'code'                  => Input::get('po_code'),
            'purchase_request_id'   => Input::get('pr_code'),
            'supplier_id'           => Input::get('supplier'),
            'status_id'             => 3,
            'created_by'            => $user->id,
            'created_at'            => $now->toDateTimeString()
        ]);

        // Create po detail
        $totalPrice = 0;
        $totalDiscount = 0;
        $totalPayment = 0;
        $qtys = Input::get('qty');
        $prices = Input::get('price');
        $discounts = Input::get('discount');
        $remarks = Input::get('remark');
        $idx = 0;

        foreach($items as $item){
            if(!empty($item)){
                $priceStr = str_replace('.','', $prices[$idx]);
                $price = (double) $priceStr;
                $qty = (double) $qtys[$idx];
                $poDetail = PurchaseOrderDetail::create([
                    'header_id'     => $poHeader->id,
                    'item_id'       => $item,
                    'quantity'      => $qty,
                    'price'         => $priceStr
                ]);

                // Check discount
                if(!empty($discounts[$idx]) && $discounts[$idx] !== '0'){
                    $poDetail->discount = $discounts[$idx];

                    $discount = (double) $discounts[$idx];
                    $discountAmount = ($qty * $price) * $discount / 100;
                    $poDetail->subtotal = ($qty * $price) - $discountAmount;

                    // Accumulate total price
                    $totalPrice += $qty * $price;

                    // Accumulate total discount
                    $totalDiscount += $discountAmount;
                }
                else{
                    $poDetail->subtotal = $qty * $price;
                    $totalPrice += $qty * $price;
                }

                if(!empty($remarks[$idx])) $poDetail->remark = $remarks[$idx];
                $poDetail->save();

                // Accumulate subtotal
                $totalPayment += $poDetail->subtotal;
            }
            $idx++;
        }

        if($totalDiscount > 0) $poHeader->total_discount = $totalDiscount;
        $poHeader->total_price = $totalPrice;
        $poHeader->total_payment = $totalPayment;
        $poHeader->save();

        Session::flash('message', 'Berhasil membuat purchase order!');

        return redirect()->route('admin.purchase_orders.show', ['purchase_order' => $poHeader]);
    }

    public function getIndex(){
        $purchaseOrders = PurchaseOrderHeader::all();
        return DataTables::of($purchaseOrders)
            ->setTransformer(new PurchaseOrderHeaderTransformer)
            ->addIndexColumn()
            ->make(true);
    }

    public function getPurchaseOrders(Request $request){
        $term = trim($request->q);
        $purchase_requests = PurchaseOrderHeader::where('code', 'LIKE', '%'. $term. '%')
            ->get();

        $formatted_tags = [];

        foreach ($purchase_requests as $purchase_request) {
            $formatted_tags[] = ['id' => $purchase_request->id, 'text' => $purchase_request->code];
        }

        return \Response::json($formatted_tags);
    }
}