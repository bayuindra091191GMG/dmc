<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 23/01/2018
 * Time: 10:45
 */

namespace App\Transformer\Stock;


use App\Models\StockIn;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class StockInTransformer extends TransformerAbstract
{
    public function transform(StockIn $stockIns){

        $createdDate = '-';
        if(!empty($stockIns->created_at)){
            $createdDate = Carbon::parse($stockIns->created_at)->format('d M Y');
        }
        $createdBy = '-';
        if(!empty($stockIns->created_by)){
            $createdBy = $stockIns->createdBy->email;
        }
        return[
            'item_code'   => $stockIns->item->code,
            'item'   => $stockIns->item->name,
            'increase'   => $stockIns->increase,
            'new_stock'   => $stockIns->new_stock,
            'created_by'    => $createdBy,
            'created_at'    => $createdDate
        ];
    }
}