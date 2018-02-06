<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 06 Feb 2018 06:33:09 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class QuotationDetail
 * 
 * @property int $id
 * @property int $header_id
 * @property int $item_id
 * @property int $quantity
 * @property float $price
 * @property int $discount
 * @property float $subtotal
 * 
 * @property \App\Models\Item $item
 * @property \App\Models\QuotationHeader $quotation_header
 *
 * @package App\Models
 */
class QuotationDetail extends Eloquent
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
		'subtotal'
	];

	public function item()
	{
		return $this->belongsTo(\App\Models\Item::class);
	}

	public function quotation_header()
	{
		return $this->belongsTo(\App\Models\QuotationHeader::class, 'header_id');
	}
}
