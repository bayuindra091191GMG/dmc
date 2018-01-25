<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 25/01/2018
 * Time: 10:41
 */

namespace App\Transformer\MasterData;

use App\Models\Item;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class ItemTransformer extends TransformerAbstract
{
    public function transform(Item $item){

        $createdDate = Carbon::parse($item->created_at)->format('d M Y');

        $action = "<a class='btn btn-xs btn-info' href='items/".$item->id."/ubah' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";

        return[
            'code'          => $item->code,
            'name'          => $item->name,
            'uom'           => $item->uom->description,
            'warehouse'     => $item->warehouse->name,
            'description'   => $item->description,
            'created_at'    => $createdDate,
            'action'        => $action
        ];
    }
}