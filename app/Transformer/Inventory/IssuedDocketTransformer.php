<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 08/02/2018
 * Time: 9:33
 */

namespace App\Transformer\Inventory;


use App\Models\IssuedDocketHeader;
use App\Models\MaterialRequestHeader;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class IssuedDocketTransformer extends TransformerAbstract
{
    public function transform(IssuedDocketHeader $header){
        $date = Carbon::parse($header->date)->format('d M Y');

        $code = "<a style='text-decoration: underline;' href='issued_dockets/detil/" . $header->id. "'>". $header->code. "</a>";

        $url = 'default';
        if($header->material_request_header->type === 1){
            $url = 'inventory';
        }
        else if($header->material_request_header->type === 2){
            $url = 'bensin';
        }
        else if($header->material_request_header->type === 3){
            $url = 'servis';
        }

        $mrCode = "<a style='text-decoration: underline;' href='material_requests/". $url. "/detil/" . $header->material_request_header_id. "' target='_blank'>". $header->material_request_header->code. "</a>";

        $action = "<a class='btn btn-xs btn-primary' href='issued_dockets/detil/". $header->id."' data-toggle='tooltip' data-placement='top'><i class='fa fa-eye'></i></a>";
        $action .= "<a style='text-decoration: underline;' class='btn btn-xs btn-info' href='issued_dockets/". $header->id."/ubah' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";

        $machinery = '-';
        if(!empty($header->unit_id)){
            $machinery = $header->machinery->code;
        }

        return[
            'no_issued_docket'  => $code,
            'department'        => $header->department->name,
            'no_unit'           => $machinery,
            'no_mr'             => $mrCode,
            'division'          => $header->division ?? '-',
            'created_at'        => $date,
            'created_by'        => $header->createdBy->email,
            'action'            => $action
        ];
    }
}