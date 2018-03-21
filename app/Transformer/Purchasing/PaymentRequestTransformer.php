<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 14/03/2018
 * Time: 15:02
 */

namespace App\Transformer\Purchasing;


use App\Models\PaymentRequest;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class PaymentRequestTransformer extends TransformerAbstract
{
    public function transform(PaymentRequest $header){
        try{
            $date = Carbon::parse($header->created_at)->format('d M Y');

            $code = "<a style='text-decoration: underline;' href='payment_requests/detil/" . $header->id. "'>". $header->code. "</a>";

            $action = "<a class='btn btn-xs btn-primary' href='payment_requests/detil/". $header->id."' data-toggle='tooltip' data-placement='top'><i class='fa fa-eye'></i></a>";
            $action .= "<a class='btn btn-xs btn-info' href='payment_requests/". $header->id."/ubah' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";

            return[
                'code'              => $code,
                'amount'            => $header->amount,
                'request_by'        => $header->createdBy()->name,
                'created_at'        => $date,
                'action'            => $action
            ];
        }catch(\Exception $ex){
            error_log($ex);
        }
    }

}