<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 02/05/2018
 * Time: 11:29
 */

namespace App\Transformer\Notification;


use App\Models\ItemStockNotification;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class ItemStockNotificationTransformer extends TransformerAbstract
{
    public function transform(ItemStockNotification $itemStockNotif){

        $createdDate = Carbon::parse($itemStockNotif->created_at)->format('d M Y');

        $code = "<a style='text-decoration: underline;' href='items/detil/" . $itemStockNotif->item->id. "'>". $itemStockNotif->item->code. "</a>";

        return[
            'code'                  => $code,
            'name'                  => $itemStockNotif->item->name,
            'uom'                   => $itemStockNotif->item->uom,
            'stock'                 => $itemStockNotif->item->stock ?? '0',
            'stock_minimum'         => $itemStockNotif->item->stock_minimum ?? '0'
        ];
    }
}