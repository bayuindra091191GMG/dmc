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
        $createdDate = Carbon::parse($header->created_at)->format('d M Y');

        $code = "<a href='quotations/detil/" . $header->id. "'>". $header->code. "</a>";
        $action = "<a class='btn btn-xs btn-info' href='quotations/". $header->id."/ubah' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";

        return[
            'code'          => $code,
            'pr_code'       => $header->purchase_request_header->code,
            'vendor'        => $header->supplier->name,
            'total_price'   => $header->total_price,
            'discount'      => $header->total_discount ?? '-',
            'total_payment' => $header->total_payment,
            'created_at'    => $createdDate,
            'action'        => $action
        ];
    }
}