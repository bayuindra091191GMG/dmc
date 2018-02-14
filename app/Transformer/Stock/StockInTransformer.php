<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 23/01/2018
 * Time: 10:45
 */

namespace App\Transformer\MasterData;


use App\Models\StockIn;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class StockInTransformer extends TransformerAbstract
{
    public function transform(StockIn $stockIns){


        $createdDate = '-';
        if(!empty($stockIns->updated_by)){
            $createdDate = Carbon::parse($stockIns->created_at)->format('d M Y');
        }
        $createdBy = '-';
        if(!empty($stockIns->updated_by)){
            $createdBy = $stockIns->createdBy->email;
        }
        return[
            'item'   => $stockIns->description,
            'depreciation'   => $stockIns->description,
            'new_stock'   => $stockIns->description,
            'created_by'    => $createdBy,
            'created_at'    => $createdDate
        ];
    }
}