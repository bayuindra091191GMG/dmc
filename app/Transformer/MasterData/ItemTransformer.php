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

        $action = "<a class='btn btn-xs btn-primary' href='items/detil/". $item->id."' data-toggle='tooltip' data-placement='top'><i class='fa fa-eye'></i></a>";
        $action .= "<a class='btn btn-xs btn-info' href='items/".$item->id."/ubah' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>";
        $action .= "<a class='delete-modal btn btn-xs btn-danger' data-id='". $item->id ."' ><i class='fa fa-trash'></i></a>";

        return[
            'name'          => $name,
            'code'          => $item->code,
            'uom'           => $item->uom->description,
            'stock'         => $item->stock ?? '-',
            'group'         => $item->group->name,
            'machinery_type' => $item->machinery_type->name ?? '-',
            'description'   => $item->description ?? '-',
            'created_at'    => $createdDate,
            'action'        => $action
        ];
    }
}