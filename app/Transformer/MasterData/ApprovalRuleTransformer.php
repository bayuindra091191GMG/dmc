<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 23/01/2018
 * Time: 10:45
 */

namespace App\Transformer\MasterData;

use App\Models\ApprovalRule;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class ApprovalRuleTransformer extends TransformerAbstract
{
    public function transform(ApprovalRule $approvalRule){

        $createdDate = Carbon::parse($approvalRule->created_at)->format('d M Y');
        $updatedDate = Carbon::parse($approvalRule->updated_at)->format('d M Y');
        $action =
            "<a class='btn btn-xs btn-info' href='approval_rules/".$approvalRule->id."/ubah' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";
        $action .= "<a class='delete-modal btn btn-xs btn-danger' data-id='". $approvalRule->id ."' ><i class='fa fa-trash'></i></a>";

        return[
            'user'          => $approvalRule->user->name,
            'document'      => $approvalRule->document->description,
            'created_by'    => $approvalRule->createdBy->email,
            'created_at'    => $createdDate,
            'updated_by'    => $approvalRule->updatedBy->email,
            'updated_at'    => $updatedDate,
            'action'        => $action
        ];
    }
}