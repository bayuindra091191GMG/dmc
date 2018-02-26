<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 26 Feb 2018 11:04:12 +0700.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class PurchaseOrderDetail
 * 
 * @property int $id
 * @property int $header_id
 * @property int $item_id
 * @property int $quantity
 * @property float $price
 * @property int $discount
 * @property float $subtotal
 * @property string $remark
 * 
 * @property \App\Models\Item $item
 * @property \App\Models\PurchaseOrderHeader $purchase_order_header
 *
 * @package App\Models
 */
class PurchaseOrderDetail extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'header_id' => 'int',
		'item_id' => 'int',
		'quantity' => 'int',
		'price' => 'float',
		'discount' => 'int',
		'subtotal' => 'float'
	];

	protected $fillable = [
		'header_id',
		'item_id',
		'quantity',
		'price',
		'discount',
		'subtotal',
		'remark'
	];

	public function item()
	{
		return $this->belongsTo(\App\Models\Item::class);
	}

	public function purchase_order_header()
	{
		return $this->belongsTo(\App\Models\PurchaseOrderHeader::class, 'header_id');
	}
}
