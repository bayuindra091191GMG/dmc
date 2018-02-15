<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 23/01/2018
 * Time: 10:45
 */

namespace App\Transformer\Stock;


use App\Models\StockAdjustment;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class StockAdjustmentTransformer extends TransformerAbstract
{
    public function transform(StockAdjustment $stockAdjustments){

        $createdDate = '-';
        if(!empty($stockAdjustments->created_at)){
            $createdDate = Carbon::parse($stockAdjustments->created_at)->format('d M Y');
        }
        $createdBy = '-';
        if(!empty($stockAdjustments->created_by)){
            $createdBy = $stockAdjustments->createdBy->email;
        }

        return[
            'item_code'   => $stockAdjustments->item->code,
            'item'   => $stockAdjustments->item->name,
            'depreciation'   => $stockAdjustments->depreciation,
            'new_stock'   => $stockAdjustments->new_stock,
            'created_by'    => $createdBy,
            'created_at'    => $createdDate
        ];
    }
}