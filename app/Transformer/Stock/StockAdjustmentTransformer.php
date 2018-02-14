<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 23/01/2018
 * Time: 10:45
 */

namespace App\Transformer\MasterData;


use App\Models\StockAdjustment;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class StockAdjustmentTransformer extends TransformerAbstract
{
    public function transform(StockAdjustment $stockAdjustments){

        $createdDate = '-';
        if(!empty($stockAdjustments->updated_by)){
            $createdDate = Carbon::parse($stockAdjustments->created_at)->format('d M Y');
        }
        $createdBy = '-';
        if(!empty($stockAdjustments->updated_by)){
            $createdBy = $stockAdjustments->createdBy->email;
        }
        return[
            'item'   => $stockAdjustments->description,
            'depreciation'   => $stockAdjustments->description,
            'new_stock'   => $stockAdjustments->description,
            'created_by'    => $createdBy,
            'created_at'    => $createdDate
        ];
    }
}