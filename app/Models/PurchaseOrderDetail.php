<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 18 Jan 2018 07:30:31 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class PurchaseOrderDetail
 * 
 * @property int $id
 * @property int $header_id
 * @property int $item_id
 * @property int $supplier_id
 * @property int $quantity
 * @property float $unit_price
 * @property float $amount
 * @property string $remark
 * 
 * @property \App\Models\Item $item
 * @property \App\Models\PurchaseOrderHeader $purchase_order_header
 * @property \App\Models\Supplier $supplier
 *
 * @package App\Models
 */
class PurchaseOrderDetail extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'header_id' => 'int',
		'item_id' => 'int',
		'supplier_id' => 'int',
		'quantity' => 'int',
		'unit_price' => 'float',
		'amount' => 'float'
	];

	protected $fillable = [
		'header_id',
		'item_id',
		'supplier_id',
		'quantity',
		'unit_price',
		'amount',
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

	public function supplier()
	{
		return $this->belongsTo(\App\Models\Supplier::class);
	}
}
