<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use App\Models\DeliveryOrderHeader;
use Illuminate\Http\Request;

class DeliveryOrderController extends Controller
{
    public function getDeliveryOrders(Request $request){
        $term = trim($request->q);
        $deliveries = DeliveryOrderHeader::where('code', 'LIKE', '%'. $term. '%')
            ->get();

        $formatted_tags = [];

        foreach ($deliveries as $delivery) {
            $formatted_tags[] = ['id' => $delivery->id, 'text' => $delivery->code];
        }

        return \Response::json($formatted_tags);
    }
}
