<?php
/**
 * Created by PhpStorm.
 * User: hellb
 * Date: 2/18/2018
 * Time: 4:51 PM
 */

namespace App\Transformer\Purchasing;


use App\Models\PurchaseOrderHeader;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class PurchaseOrderHeaderTransformer extends TransformerAbstract
{
    protected $mode = 'default';

    public function __construct($mode)
    {
        $this->mode = $mode;
    }

    public function transform(PurchaseOrderHeader $header){
        try{
            $date = Carbon::parse($header->date)->format('d M Y');

            $code = "<a href='purchase_orders/detil/" . $header->id. "'>". $header->code. "</a>";
            $prCode =  "<a href='purchase_requests/detil/" . $header->purchase_request_id. "'>". $header->purchase_request_header->code. "</a>";

            $action = "";
            $route = route('admin.purchase_invoices.create', ['po' => $header->id]);

            if($this->mode === 'default'){
                $action = "<a class='btn btn-xs btn-primary' href='purchase_orders/detil/". $header->id."' data-toggle='tooltip' data-placement='top'><i class='fa fa-eye'></i></a>";
                $action .= "<a class='btn btn-xs btn-info' href='purchase_orders/". $header->id."/ubah' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";
            }else{
                $action = "<a class='btn btn-xs btn-success' href='". $route. "' data-toggle='tooltip' data-placement='top'><i class='fa fa-check-square'></i> Proses Invoice </a>";
            }


            return[
                'code'              => $code,
                'pr_code'           => $prCode,
                'supplier'          => $header->supplier->name,
                'total_price'       => $header->total_price_string,
                'total_discount'    => $header->total_discount_string ?? '-',
                'delivery_fee'      => $header->delivery_fee_string ?? '-',
                'total_payment'     => $header->total_payment_string,
                'created_at'        => $date,
                'status'            => $header->status_id == 3 ? 'open' : 'closed',
                'action'            => $action
            ];
        }catch(\Exception $ex){
            error_log($ex);
        }
    }
}