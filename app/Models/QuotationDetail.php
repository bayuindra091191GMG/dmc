<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 19 Apr 2018 14:10:09 +0700.
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
 * @property int $discount_percent
 * @property float $discount_amount
 * @property float $subtotal
 * @property string $remark
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
		'discount_percent' => 'int',
		'discount_amount' => 'float',
		'subtotal' => 'float'
	];

    protected $appends = [
        'priceString',
        'discountString',
        'discountAmountString',
        'subtotalString'
    ];

	protected $fillable = [
		'header_id',
		'item_id',
		'quantity',
		'price',
		'discount_percent',
		'discount_amount',
		'subtotal',
		'remark'
	];

    public function getPriceStringAttribute(){
        return number_format($this->attributes['price'], 0, ",", ".");
    }

    public function getDiscountStringAttribute(){
        return $this->attributes['discount_percent']. '%';
    }

    public function getDiscountAmountStringAttribute(){
        return number_format($this->attributes['discount_amount'], 0, ",", ".");
    }

    public function getSubtotalStringAttribute(){
        return number_format($this->attributes['subtotal'], 0, ",", ".");
    }

	public function item()
	{
		return $this->belongsTo(\App\Models\Item::class);
	}

	public function quotation_header()
	{
		return $this->belongsTo(\App\Models\QuotationHeader::class, 'header_id');
	}
}
