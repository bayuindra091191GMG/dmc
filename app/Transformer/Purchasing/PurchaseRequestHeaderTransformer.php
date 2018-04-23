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
    protected $mode = 'default';

    public function __construct($mode)
    {
        $this->mode = $mode;
    }

    public function transform(PurchaseRequestHeader $header){
        $date = Carbon::parse($header->date)->format('d M Y');
        $createdAt = Carbon::parse($header->created_at)->format('d M Y - H:i');
        $priorityLimitDate = Carbon::parse($header->priority_limit_date)->format('d M Y');

        $code = "<a href='purchase_requests/detil/" . $header->id. "' style='text-decoration: underline;'>". $header->code. "</a>";

        // Check MR type
        if($header->material_request_header->type === 1){
            $url = 'inventory';
        }
        else if($header->material_request_header->type === 2){
            $url = 'bbm';
        }
        else if($header->material_request_header->type === 3){
            $url = 'oli';
        }
        else{
            $url = 'servis';
        }

        $mrCode = "<a href='/admin/material_requests/". $url. "/detil/" . $header->material_request_id. "' style='text-decoration: underline;' target='_blank'>". $header->material_request_header->code. "</a>";

        $action = "";
        if($this->mode === 'default'){
            $action = "<a class='btn btn-xs btn-primary' href='purchase_requests/detil/". $header->id."' data-toggle='tooltip' data-placement='top'><i class='fa fa-eye'></i></a>";
            $action .= "<a class='btn btn-xs btn-info' href='purchase_requests/". $header->id."/ubah' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";
        }
        elseif($this->mode === 'before_create_rfq'){
            $route = route('admin.quotations.create', ['pr' => $header->id]);
            $action = "<a class='btn btn-xs btn-success' href='". $route. "' data-toggle='tooltip' data-placement='top'><i class='fa fa-check-square'></i> Proses RFQ </a>";
        }
        else{
            $route = route('admin.purchase_orders.create', ['pr' => $header->id]);
            $action = "<a class='btn btn-xs btn-success' href='". $route. "' data-toggle='tooltip' data-placement='top'><i class='fa fa-check-square'></i> Proses PO </a>";
        }

        $machinery = '-';
        if(!empty($header->machinery_id)){
            $machinery = $header->machinery->code;
        }

        return[
            'code'          => $code,
            'mr_code'       => $mrCode,
            'priority'      => $header->priority,
            'limit_date'    => $priorityLimitDate,
            'department'    => $header->department->name,
            'machinery'     => $machinery,
            'date'          => $date,
            'status'        => $header->status->description,
            'created_at'    => $createdAt,
            'action'        => $action
        ];
    }
}