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
use League\Fractal\TransformerAbstract;

class PurchaseOrderHeaderTransformer extends TransformerAbstract
{
    public function transform(PurchaseOrderHeader $header){
        try{
            $createdDate = Carbon::parse($header->created_at)->format('d M Y');

            $code = "<a href='purchase_orders/detil/" . $header->id. "'>". $header->code. "</a>";
            $prCode =  "<a href='purchase_requests/detil/" . $header->purchase_request_id. "'>". $header->purchase_request_header->code. "</a>";

            $action = "<a class='btn btn-xs btn-primary' href='purchase_orders/detil/". $header->id."' data-toggle='tooltip' data-placement='top'><i class='fa fa-eye'></i></a>";
            $action .= "<a class='btn btn-xs btn-info' href='purchase_orders/". $header->id."/ubah' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";

            return[
                'code'              => $code,
                'pr_code'           => $prCode,
                'supplier'          => $header->supplier->name,
                'total_price'       => $header->total_price_string,
                'total_discount'    => $header->total_discount_string ?? '-',
                'delivery_fee'      => $header->delivery_fee_string ?? '-',
                'total_payment'     => $header->total_payment_string,
                'created_at'        => $createdDate,
                'status'            => $header->status_id == 3 ? 'open' : 'closed',
                'action'            => $action
            ];
        }catch(\Exception $ex){
            error_log($ex);
        }
    }
}