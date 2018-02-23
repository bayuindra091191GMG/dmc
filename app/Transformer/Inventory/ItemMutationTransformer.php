<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 23/01/2018
 * Time: 10:45
 */

namespace App\Transformer\Inventory;


use App\Models\ItemMutation;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class ItemMutationTransformer extends TransformerAbstract
{
    public function transform(ItemMutation $stockMutations){
        $createdDate = '-';
        if(!empty($stockMutations->created_at)){
            $createdDate = Carbon::parse($stockMutations->created_at)->format('d M Y');
        }
        $createdBy = '-';
        if(!empty($stockMutations->created_by)){
            $createdBy = $stockMutations->createdBy->email;
        }

        return[
            'item_code'   => $stockMutations->item->code,
            'item'   => $stockMutations->item->name,
            'from_warehouse'   => $stockMutations->warehouseFrom->name,
            'to_warehouse'   => $stockMutations->warehouseTo->name,
            'mutation_quantity'   => $stockMutations->mutation_quantity,
            'created_by'    => $createdBy,
            'created_at'    => $createdDate
        ];
    }
}