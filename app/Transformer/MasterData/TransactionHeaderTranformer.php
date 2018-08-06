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

//        $date = Carbon::parse($header->date)->format('d M Y');
        $date = Carbon::parse($header->date)->toIso8601String();

        $trxShowUrl = route('admin.transactions.show', ['transaction' => $header->id]);

        if($header->type === 1){
            $type = 'NORMAL';
            $trxEditUrl = route('admin.transactions.edit', ['transaction' => $header->id]);
        }
        else if($header->type === 2){
            $type = 'PRORATE';
            $trxEditUrl = route('admin.transactions.prorate.edit', ['prorate' => $header->id]);
        }
        else{
            $type = 'PRIVATE';
            $trxEditUrl = route('admin.transactions.private.edit', ['private' => $header->id]);
        }

        $action = "<a class='btn btn-xs btn-primary' href='". $trxShowUrl."' data-toggle='tooltip' data-placement='top'><i class='fa fa-eye'></i></a>";
        $action .= "<a class='btn btn-xs btn-info' href='". $trxEditUrl."' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";

        if($header->type === 1 || $header->type === 3){
            $totalPrice = $header->total_price_string;
        }
        else{
            $totalPrice = $header->total_prorate_price_string;
        }

        return[
            'code'              => $header->code,
            'invoice'           => $header->invoice_number,
            'type'              => $type,
            'date'              => $date,
            'fee'               => $header->registration_fee_string,
            'total_price'       => $totalPrice,
//            'total_discount'    => $header->total_discount_string,
            'total_payment'     => $header->total_payment_string,
            'action'            => $action
        ];
    }
}