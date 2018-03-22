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

        $routeBefore = route('admin.items.show',['item' => $header->item_id_before]);
        $routeAfter = route('admin.items.show',['item' => $header->item_id_after]);
        $itemCodeBefore = "<a style='text-decoration: underline;' href='". $routeBefore. "'>". $header->itemBefore->code. "</a>";
        $itemCodeAfter = "<a style='text-decoration: underline;' href='". $routeAfter. "'>". $header->itemAfter->code. "</a>";

        return[
            'item_code_before'      => $itemCodeBefore,
            'item_name_before'      => $header->itemBefore->name,
            'item_code_after'       => $itemCodeAfter,
            'item_name_after'       => $header->itemAfter->name,
            'created_at'            => $createdDate,
            'created_by'            => $header->createdBy->email
        ];
    }
}