<?php
/**
 * Created by PhpStorm.
 * User: hellb
 * Date: 2/18/2018
 * Time: 4:51 PM
 */

namespace App\Transformer\Purchasing;


use App\Models\PurchaseOrderHeader;
use Carbon\Carbon;

class PurchaseOrderHeaderTransformer
{
    public function transform(PurchaseOrderHeader $header){
        $createdDate = Carbon::parse($header->created_at)->format('d M Y');

        $code = "<a href='purchase_orders/detil/" . $header->id. "'>". $header->code. "</a>";
        $prCode =  "<a href='purchase_requests/detil/" . $header->purchasing_request_id. "'>". $header->purchase_request_header->code. "</a>";
        $action = "<a class='btn btn-xs btn-info' href='purchase_orders/". $header->id."/ubah' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";



        return[
            'code'              => $code,
            'pr_code'           => $prCode,
            'supplier'          => $header->supplier->name,
            'delivery_charge'   => $header->delivery_charge ?? '-',
            'total_price'       => $header->total_price ?? '-',
            'created_at'        => $createdDate,
            'status'            => $header->status_id == 1 ? 'open' : 'closed',
            'action'            => $action
        ];
    }
}