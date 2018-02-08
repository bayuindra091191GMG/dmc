<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 08/02/2018
 * Time: 9:33
 */

namespace App\Transformer\Purchasing;


use App\Models\PurchaseRequestHeader;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class PurchaseRequestHeaderTransformer extends TransformerAbstract
{
    public function transform(PurchaseRequestHeader $header){
        $createdDate = Carbon::parse($header->created_at)->format('d M Y');

        $code = "<a href='purchase_requests/detil/" . $header->id. "'>". $header->code. "</a>";
        $action = "<a class='btn btn-xs btn-info' href='items/". $header->id."/ubah' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";

        $machinery = '-';
        if(!empty($header->machinery_id)){
            $machinery = $header->machinery->code;
        }

        return[
            'code'          => $code,
            'department'    => $header->department->name,
            'machinery'     => $machinery,
            'sn_chasis'     => $header->sn_chasis ?? '-',
            'sn_engine'     => $header->sn_engine ?? '-',
            'created_at'    => $createdDate,
            'action'        => $action
        ];
    }
}