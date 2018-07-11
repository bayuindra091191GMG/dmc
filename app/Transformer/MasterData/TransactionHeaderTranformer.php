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

        $trxShowUrl = route('admin.transactions.show', ['transaction' => $header->id]);
        $trxEditUrl = route('admin.transactions.edit', ['transaction' => $header->id]);

        $action = "<a class='btn btn-xs btn-info' href='". $trxShowUrl."' data-toggle='tooltip' data-placement='top'><i class='fa fa-eye'></i></a>";
        $action .= "<a class='btn btn-xs btn-info' href='". $trxEditUrl."' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";

        $type = $header->type === 1 ? 'NORMAL' : 'PRORATE';

        return[
            'code'              => $header->code,
            'invoice'           => $header->invoice_number,
            'type'              => $type,
            'date'              => $date,
            'total_price'       => $header->total_price_string,
            'total_discount'    => $header->total_discount_string,
            'total_payment'     => $header->total_payment_string,
            'action'            => $action
        ];
    }
}