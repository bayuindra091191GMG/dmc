<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 19/02/2018
 * Time: 10:29
 */

namespace App\Transformer\Purchasing;


use App\Models\QuotationHeader;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class QuotationHeaderTransformer extends TransformerAbstract
{
    public function transform(QuotationHeader $header){
        try{
            $createdDate = Carbon::parse($header->date)->format('d M Y');

            $code = "<a href='quotations/detil/" . $header->id. "' target='_blank'>". $header->code. "</a>";
            $action = "<a class='btn btn-xs btn-info' href='quotations/". $header->id."/ubah' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";

            return[
                'code'          => $code,
                'pr_code'       => $header->purchase_request_header->code,
                'vendor'        => $header->supplier->name,
                'total_price'   => $header->total_price_string,
                'discount'      => $header->total_discount_string ?? '0',
                'delivery_fee'  => $header->delivery_fee_string ?? '0',
                'ppn'           => $header->ppn_string ?? '0',
                'pph'           => $header->pph_string ?? '0',
                'total_payment' => $header->total_payment_string,
                'status'        => $header->status->description,
                'created_at'    => $createdDate,
                'action'        => $action
            ];
        }
        catch(\Exception $ex){
            error_log($ex);
        }
    }
}