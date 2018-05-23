<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 23/05/2018
 * Time: 14:56
 */

namespace App\Transformer\MasterData;


use App\Models\TransactionHeader;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class TransactionHeaderTranformer extends TransformerAbstract
{
    public function transform(TransactionHeader $header){

        $date = Carbon::parse($header->date)->format('d M Y');
        $action = "<a class='btn btn-xs btn-info' href='transactions/show/".$header->id."' data-toggle='tooltip' data-placement='top'><i class='fa fa-eye'></i></a>";
        $action .= "<a class='btn btn-xs btn-info' href='transactions/edit/".$header->id."' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";

        return[
            'code'              => $header->code,
            'date'              => $date,
            'invoice'           => $header->invoice_number,
            'total_price'       => $header->total_price_string,
            'total_discount'    => $header->total_discount_string,
            'total_payment'     => $header->total_payment_string,
            'action'            => $action
        ];
    }
}