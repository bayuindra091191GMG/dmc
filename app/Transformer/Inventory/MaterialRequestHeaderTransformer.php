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

        $url = 'default';
        if($this->type === 'other'){
            $url = 'inventory';
        }
        else if($this->type === 'fuel'){
            $url = 'bensin';
        }
        else if($this->type === 'service'){
            $url = 'servis';
        }

        $typeStr = 'default';
        if($header->type === 1){
            $typeStr = 'Inventory';
        }
        else if($header->type === 2){
            $typeStr = 'Oli & Bensin';
        }
        else{
            $typeStr = 'Servis';
        }

        $code = "<a href='/admin/material_requests/". $url. "/detil/" . $header->id. "' style='text-decoration: underline;'>". $header->code. "</a>";

        $action = "";
        $route = route('admin.purchase_requests.create', ['mr' => $header->id]);
        if($this->type !== 'before_create'){
            $action = "<a class='btn btn-xs btn-primary' href='/admin/material_requests/". $url. "/detil/". $header->id."' data-toggle='tooltip' data-placement='top'><i class='fa fa-eye'></i></a>";
            $action .= "<a class='btn btn-xs btn-info' href='/admin/material_requests/". $url. "/". $header->id."/ubah' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";
            $action .= "<a class='btn btn-xs btn-success' href='". $route. "' data-toggle='tooltip' data-placement='top'><i class='fa fa-check-square'></i> Proses PR </a>";
        }
        else{
            $action = "<a class='btn btn-xs btn-success' href='". $route. "' data-toggle='tooltip' data-placement='top'><i class='fa fa-check-square'></i> Proses PR </a>";
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
            'created_at'    => $date,
            'action'        => $action
        ];
    }
}