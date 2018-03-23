<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 06 Feb 2018 06:33:34 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ItemReceiptDetail
 * 
 * @property int $id
 * @property int $header_id
 * @property int $item_id
 * @property int $quantity
 * @property int $purchase_order_id
 * @property string $remark
 * 
 * @property \App\Models\Item $item
 * @property \App\Models\ItemReceiptHeader $item_receipt_header
 *
 * @package App\Models
 */
class ItemReceiptDetail extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'header_id' => 'int',
		'item_id' => 'int',
		'quantity' => 'int',
		'purchase_order_id' => 'int'
	];

    protected $appends = array('poCode');

	protected $fillable = [
		'header_id',
		'item_id',
		'quantity',
		'remark'
	];

	public function getPoCodeAttribute($value){
        $poCode = null;
        if ($this->purchase_order_header) {
            $poCode = $this->purchase_order_header->code;
        }
        return $poCode;
    }

	public function item()
	{
		return $this->belongsTo(\App\Models\Item::class);
	}

	public function item_receipt_header()
	{
		return $this->belongsTo(\App\Models\ItemReceiptHeader::class, 'header_id');
	}
}
