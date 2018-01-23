<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 23/01/2018
 * Time: 10:45
 */

namespace App\Transformer\MasterData;


use App\Models\PaymentMethod;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class PaymentMethodTransformer extends TransformerAbstract
{
    public function transform(PaymentMethod $payment_method){

        $action =
            "<a class='btn btn-xs btn-info' href='payment_methods/".$payment_method->id."/ubah' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";

        return[
            'description'   => $payment_method->description,
            'fee'           => $payment_method->fee,
            'status'        => $payment_method->status->description,
            'action'        => $action
        ];
    }
}