<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 21 Feb 2018 06:50:13 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class DeliveryOrderDetail
 * 
 * @property int $id
 * @property int $header_id
 * @property int $item_id
 * @property string $remarks
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
		'item_id' => 'int'
	];

	protected $fillable = [
		'header_id',
		'item_id',
		'remarks'
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
