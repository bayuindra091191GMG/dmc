<?php
/**
 * Created by PhpStorm.
 * User: hellb
 * Date: 2/18/2018
 * Time: 4:45 PM
 */

namespace App\Http\Controllers\Admin\Purchasing;


use App\Http\Controllers\Controller;
use App\Models\PurchaseOrderHeader;
use App\Transformer\Purchasing\PurchaseOrderHeaderTransformer;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;

class PurchaseOrderHeaderController extends Controller
{
    public function index(){

        return View('admin.purchasing.purchase_orders.index');
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