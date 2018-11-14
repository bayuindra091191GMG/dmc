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
        else if($header->type === 3){
            $type = 'PRIVATE';
            $trxEditUrl = route('admin.transactions.private.edit', ['private' => $header->id]);
        }
        else{
            $type = 'CUTI';
        }

        $action = "<a class='btn btn-xs btn-primary' href='". $trxShowUrl."' data-toggle='tooltip' data-placement='top'><i class='fa fa-eye'></i></a>";

        if($header->type !== 4){
            $action .= "<a class='btn btn-xs btn-info' href='". $trxEditUrl."' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";
        }

        if($header->type === 1 || $header->type === 3 || $header->type === 4){
            $totalPrice = $header->total_price;
        }
        else{
            $totalPrice = $header->total_prorate_price ?? 0;
        }

        return[
            'code'              => $header->code,
            'invoice'           => $header->invoice_number,
            'type'              => $type,
            'date'              => $date,
            'customer'          => $header->customer->name,
            'payment_method'    => $header->payment_method,
            'fee'               => $header->registration_fee,
            'total_price'       => $totalPrice,
//            'total_discount'    => $header->total_discount_string,
            'total_payment'     => $header->total_payment,
            'action'            => $action
        ];
    }
}