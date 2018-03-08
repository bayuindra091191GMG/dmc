<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 08 Mar 2018 14:21:50 +0700.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class DeliveryOrderDetail
 * 
 * @property int $id
 * @property int $header_id
 * @property int $item_id
 * @property int $quantity
 * @property string $remark
 * 
 * @property \App\Models\DeliveryOrderHeader $delivery_order_header
 * @property \App\Models\Item $item
 *
 * @package App\Models
 */
class DeliveryOrderDetail extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'header_id' => 'int',
		'item_id' => 'int',
		'quantity' => 'int'
	];

	protected $fillable = [
		'header_id',
		'item_id',
		'quantity',
		'remark'
	];

	public function delivery_order_header()
	{
		return $this->belongsTo(\App\Models\DeliveryOrderHeader::class, 'header_id');
	}

	public function item()
	{
		return $this->belongsTo(\App\Models\Item::class);
	}
}
