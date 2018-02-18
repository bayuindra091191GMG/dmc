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
}