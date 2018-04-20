<?php
/**
 * Created by PhpStorm.
 * User: hellb
 * Date: 3/20/2018
 * Time: 11:35 AM
 */

namespace App\Transformer\Inventory;


use App\Models\MaterialRequestHeader;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class MaterialRequestHeaderTransformer extends TransformerAbstract
{
    protected $type = 'default';

    public function __construct($type)
    {
        $this->type = $type;
    }

    public function transform(MaterialRequestHeader $header){
        $date = Carbon::parse($header->date)->format('d M Y');
        $createdAt = Carbon::parse($header->created_at)->format('d M Y');

        $url = 'default';
        if($header->type === 1){
            $url = 'inventory';
            $typeStr = 'Inventory';
        }
        else if($header->type === 2){
            $url = 'bbm';
            $typeStr = 'BBM';
        }
        else if($header->type === 3){
            $url = 'oli';
            $typeStr = 'Oli';
        }
        else{
            $url = 'servis';
            $typeStr = 'Servis';
        }

        $code = "<a href='/admin/material_requests/". $url. "/detil/" . $header->id. "' style='text-decoration: underline;'>". $header->code. "</a>";

        $action = "";
        $route = route('admin.purchase_requests.create', ['mr' => $header->id]);
        if($this->type == 'default'){
            $action .= "<a class='btn btn-xs btn-primary' href='/admin/material_requests/". $url. "/detil/". $header->id."' data-toggle='tooltip' data-placement='top'><i class='fa fa-eye'></i></a>";
            $action .= "<a class='btn btn-xs btn-info' href='/admin/material_requests/". $url. "/". $header->id."/ubah' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";
        }
        else if($this->type === 'before_create_id'){
            $route .= route('admin.issued_dockets.create', ['mr' => $header->id]);
            $action = "<a class='btn btn-xs btn-success' href='". $route . "' data-toggle='tooltip' data-placement='top'><i class='fa fa-check-square'></i> Proses Issued Docket </a>";
        }
        else{
            $action = "<a class='btn btn-xs btn-success' href='". $route . "' data-toggle='tooltip' data-placement='top'><i class='fa fa-check-square'></i> Proses PR </a>";
        }

        $machinery = '-';
        if(!empty($header->machinery_id)){
            $machinery = $header->machinery->code;
        }

        return[
            'code'          => $code,
            'type'          => $typeStr,
            'department'    => $header->department->name,
            'machinery'     => $machinery,
            'date'          => $date,
            'status'        => $header->status->description,
            'created_at'    => $createdAt,
            'action'        => $action
        ];
    }
}