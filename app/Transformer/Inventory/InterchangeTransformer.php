<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 08/02/2018
 * Time: 9:33
 */

namespace App\Transformer\Inventory;


use App\Models\Interchange;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class InterchangeTransformer extends TransformerAbstract
{
    public function transform(Interchange $header){
        $createdDate = Carbon::parse($header->created_at)->format('d M Y');

        $itemBefore = $header->itemBefore->name;
        $itemAfter = $header->itemAfter->name;

        return[
            'item_before'       => $itemBefore,
            'item_after'        => $itemAfter,
            'created_at'        => $createdDate,
            'created_by'        => $header->createdBy->email
        ];
    }
}