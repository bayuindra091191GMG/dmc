<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 23/01/2018
 * Time: 10:45
 */

namespace App\Transformer\MasterData;

use App\Models\Voucher;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class VoucherTransformer extends TransformerAbstract
{
    public function transform(Voucher $voucher){

        $createdDate = Carbon::parse($voucher->created_at)->format('d M Y');
        $action =
            "<a class='btn btn-xs btn-info' href='vouchers/".$voucher->id."/edit' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";
        $action .= "<a class='delete-modal btn btn-xs btn-danger' data-id='". $voucher->id ."' ><i class='fa fa-trash'></i></a>";

        return[
            'name'          => $voucher->name,
            'description'   => $voucher->description,
            'type'          => $voucher->type,
            'point_needed'  => $voucher->point_needed,
            'status'        => $voucher->status->description,
            'created_at'    => $createdDate,
            'action'        => $action
        ];
    }
}
