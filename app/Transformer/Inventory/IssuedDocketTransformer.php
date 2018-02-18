<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 08/02/2018
 * Time: 9:33
 */

namespace App\Transformer\Inventory;


use App\Models\IssuedDocketHeader;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class IssuedDocketTransformer extends TransformerAbstract
{
    public function transform(IssuedDocketHeader $header){
        $createdDate = Carbon::parse($header->created_at)->format('d M Y');

        $code = "<a href='issued_dockets/detil/" . $header->id. "'>". $header->code. "</a>";
        $action = "<a class='btn btn-xs btn-info' href='issued_dockets/". $header->id."/ubah' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";

        $machinery = '-';
        if(!empty($header->unit_id)){
            $machinery = $header->machinery->code;
        }

        return[
            'no_issued_docket'  => $code,
            'department'        => $header->department->name,
            'no_unit'           => $machinery,
            'no_pr'             => $header->purchase_request_header->code ?? '-',
            'division'          => $header->division,
            'created_at'        => $createdDate,
            'created_by'        => $header->createdBy->email,
            'action'            => $action
        ];
    }
}