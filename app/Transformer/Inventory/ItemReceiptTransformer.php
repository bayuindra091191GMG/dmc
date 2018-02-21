<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 08/02/2018
 * Time: 9:33
 */

namespace App\Transformer\Inventory;


use App\Models\ItemReceiptHeader;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class ItemReceiptTransformer extends TransformerAbstract
{
    public function transform(ItemReceiptHeader $header){
        $createdDate = Carbon::parse($header->created_at)->format('d M Y');
        $date = Carbon::parse($header->date)->format('d M Y');

        $code = "<a href='item_receipts/detil/" . $header->id. "'>". $header->code. "</a>";
        $action = "<a class='btn btn-xs btn-info' href='item_receipts/". $header->id."/ubah' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";

        return[
            'code'              => $code,
            'no_sj_spb'         => $header->no_sj_spb,
            'date'              => $date,
            'delivery_order'    => $header->delivery_order_header->code ?? '-',
            'created_at'        => $createdDate,
            'created_by'        => $header->createdBy->email,
            'action'            => $action
        ];
    }
}