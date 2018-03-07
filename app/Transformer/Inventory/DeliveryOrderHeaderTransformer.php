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
            $createdDate = Carbon::parse($header->created_at)->format('d M Y');

            $code = "<a href='delivery_orders/detil/" . $header->id. "'>". $header->code. "</a>";

            $prCode = "-";
            if(!empty($header->machinery_id)){
                $prCode =  "<a href='purchase_requests/detil/" . $header->purchase_request_id. "'>". $header->purchase_request_header->code. "</a>";
            }

            $action = "<a class='btn btn-xs btn-primary' href='delivery_orders/detil/". $header->id."' data-toggle='tooltip' data-placement='top'><i class='fa fa-eye'></i></a>";
            $action .= "<a class='btn btn-xs btn-info' href='delivery_orders/". $header->id."/ubah' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";

            return[
                'code'              => $code,
                'from_site'         => $header->fromSite->name,
                'to_site'           => $header->toSite->name,
                'machinery'         => $header->machinery->code ?? "-",
                'pr_code'           => $prCode,
                'remark'            => $header->remark ?? "-",
                'created_at'        => $createdDate,
                'status'            => $header->status_id == 3 ? 'open' : 'closed',
                'action'            => $action
            ];
        }catch(\Exception $ex){
            error_log($ex);
        }
    }
}