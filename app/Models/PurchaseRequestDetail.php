<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 18 Jan 2018 07:30:31 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class PurchaseRequestDetail
 * 
 * @property int $id
 * @property int $header_id
 * @property int $item_id
 * @property int $quantity
 * @property string $remark
 * @property \Carbon\Carbon $delivery_date
 * 
 * @property \App\Models\Item $item
 * @property \App\Models\PurchaseRequestHeader $purchase_request_header
 *
 * @package App\Models
 */
class PurchaseRequestDetail extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'header_id' => 'int',
		'item_id' => 'int',
		'quantity' => 'int'
	];

	protected $dates = [
		'delivery_date'
	];

	protected $fillable = [
		'header_id',
		'item_id',
		'quantity',
		'remark',
		'delivery_date'
	];

	public function item()
	{
		return $this->belongsTo(\App\Models\Item::class);
	}

	public function purchase_request_header()
	{
		return $this->belongsTo(\App\Models\PurchaseRequestHeader::class, 'header_id');
	}
}
