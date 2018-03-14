<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 14/03/2018
 * Time: 15:02
 */

namespace App\Transformer\Purchasing;


use App\Models\PurchaseInvoiceHeader;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class PurchaseInvoiceHeaderTransformer extends TransformerAbstract
{
    public function transform(PurchaseInvoiceHeader $header){
        try{
            $createdDate = Carbon::parse($header->created_at)->format('d M Y');

            $code = "<a href='purchase_invoices/detil/" . $header->id. "'>". $header->code. "</a>";
            $poCode =  "<a href='purchase_invoices/detil/" . $header->purchase_order_id. "'>". $header->purchase_order_header->code. "</a>";

            $action = "<a class='btn btn-xs btn-primary' href='purchase_invoices/detil/". $header->id."' data-toggle='tooltip' data-placement='top'><i class='fa fa-eye'></i></a>";
            $action .= "<a class='btn btn-xs btn-info' href='purchase_invoices/". $header->id."/ubah' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";

            return[
                'code'              => $code,
                'po_code'           => $poCode,
                'supplier'          => $header->purchase_order_header->supplier->name,
                'total_price'       => $header->total_price_string,
                'total_discount'    => $header->total_discount_string ?? '-',
                'delivery_fee'      => $header->delivery_fee_string ?? '-',
                'total_payment'     => $header->total_payment_string,
                'created_at'        => $createdDate,
                'action'            => $action
            ];
        }catch(\Exception $ex){
            error_log($ex);
        }
    }

}