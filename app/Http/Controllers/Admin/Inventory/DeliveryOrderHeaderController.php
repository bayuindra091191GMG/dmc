<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 02/03/2018
 * Time: 14:56
 */

namespace App\Http\Controllers\Admin\Inventory;


use App\Http\Controllers\Controller;
use App\Models\DeliveryOrderHeader;
use App\Transformer\Inventory\DeliveryOrderHeaderTransformer;
use Yajra\DataTables\Facades\DataTables;

class DeliveryOrderHeaderController extends Controller
{
    public function index(){
        return View('admin.inventory.delivery_orders.index');
    }

    public function create(){
        return View('admin.inventory.delivery_orders.create');
    }

    public function getIndex(){
        $deliveryOrders = DeliveryOrderHeader::dateDescending()->get();
        return DataTables::of($deliveryOrders)
            ->setTransformer(new DeliveryOrderHeaderTransformer)
            ->addIndexColumn()
            ->make(true);
    }
}