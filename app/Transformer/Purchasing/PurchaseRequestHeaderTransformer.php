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

        $code = "<a href='purchase_requests/detil/" . $header->id. "' style='text-decoration: underline;'>". $header->code. "</a>";

        $action = "";
        $route = route('admin.purchase_orders.create', ['pr' => $header->id]);
        if($this->mode === 'default'){
            $action = "<a class='btn btn-xs btn-primary' href='purchase_requests/detil/". $header->id."' data-toggle='tooltip' data-placement='top'><i class='fa fa-eye'></i></a>";
            $action .= "<a class='btn btn-xs btn-info' href='purchase_requests/". $header->id."/ubah' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";
            $action .= "<a class='btn btn-xs btn-success' href='". $route. "' data-toggle='tooltip' data-placement='top'><i class='fa fa-check-square'></i> Proses PO </a>";
        }
        else{
            $action = "<a class='btn btn-xs btn-success' href='". $route. "' data-toggle='tooltip' data-placement='top'><i class='fa fa-check-square'></i> Proses PO </a>";
        }

        $machinery = '-';
        if(!empty($header->machinery_id)){
            $machinery = $header->machinery->code;
        }

        return[
            'code'          => $code,
            'department'    => $header->department->name,
            'machinery'     => $machinery,
            'created_at'    => $date,
            'action'        => $action
        ];
    }
}