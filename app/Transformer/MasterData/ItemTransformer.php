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

        $name = "<a href='items/detil/" . $item->id. "'>". $item->name. "</a>";
        $action = "<a class='btn btn-xs btn-info' href='items/".$item->id."/ubah' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";

        return[
            'name'          => $name,
            'code'          => $item->code,
            'uom'           => $item->uom->description,
            'group'         => $item->group->name,
            'warehouse'     => $item->warehouse->name,
            'stock'         => $item->stock ?? 0,
            'description'   => $item->description ?? '-',
            'created_at'    => $createdDate,
            'action'        => $action
        ];
    }
}