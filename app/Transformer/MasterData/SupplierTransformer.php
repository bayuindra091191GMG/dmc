<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 23/01/2018
 * Time: 10:45
 */

namespace App\Transformer\MasterData;

use App\Models\ApprovalRule;
use App\Models\Supplier;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class SupplierTransformer extends TransformerAbstract
{
    public function transform(Supplier $supplier){

        $createdDate = Carbon::parse($supplier->created_at)->format('d M Y');
        $updatedDate = Carbon::parse($supplier->updated_at)->format('d M Y');
        $contractStart = Carbon::parse($supplier->contract_start_date)->format('d M Y');
        $contractFinish = Carbon::parse($supplier->contract_finish_date)->format('d M Y');
        $action =
            "<a class='btn btn-xs btn-info' href='suppliers/".$supplier->id."/ubah' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";

        return[
            'code'              => $supplier->code,
            'name'              => $supplier->name,
            'email'             => $supplier->email,
            'phone'             => $supplier->phone,
            'contract_start'    => $contractStart,
            'contract_finish'   => $contractFinish,
            'created_by'        => $supplier->createdBy->email,
            'created_at'        => $createdDate,
            'updated_by'        => $supplier->updatedBy->email,
            'updated_at'        => $updatedDate,
            'action'            => $action
        ];
    }
}