<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 02/03/2018
 * Time: 15:04
 */

namespace App\Transformer\Inventory;


use App\Models\DeliveryOrderHeader;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class DeliveryOrderHeaderTransformer extends TransformerAbstract
{
    public function transform(DeliveryOrderHeader $header){
        try{
            $date = Carbon::parse($header->date)->format('d M Y');

            $code = "<a href='delivery_orders/detil/" . $header->id. "'>". $header->code. "</a>";

            $prCode = "-";
            if(!empty($header->machinery_id)){
                $prCode =  "<a href='purchase_requests/detil/" . $header->purchase_request_id. "'>". $header->purchase_request_header->code. "</a>";
            }

            $action = "<a class='btn btn-xs btn-primary' href='delivery_orders/detil/". $header->id."' data-toggle='tooltip' data-placement='top'><i class='fa fa-eye'></i></a>";
            $action .= "<a class='confirm-modal btn btn-xs btn-success' data-id='". $header->id ."' ><i class='fa fa-check-square'></i></a>";
            $action .= "<a class='cancel-modal btn btn-xs btn-danger' data-id='". $header->id ."' ><i class='fa fa-times-circle'></i></a>";

            return[
                'code'              => $code,
                'from_warehouse'    => $header->toWarehouse->name,
                'to_warehouse'      => $header->fromWarehouse->name,
                'machinery'         => $header->machinery->code ?? "-",
                'pr_code'           => $prCode,
                'remark'            => $header->remark ?? "-",
                'created_at'        => $date,
                'status'            => $header->status->description,
                'action'            => $action
            ];
        }catch(\Exception $ex){
            error_log($ex);
        }
    }
}