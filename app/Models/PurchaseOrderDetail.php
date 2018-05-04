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
 * @property int $received_quantity
 * @property int $quantity_invoiced
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
		'discount' => 'float',
        'received_quantity' => 'int',
        'quantity_invoiced' => 'int',
		'subtotal' => 'float'
	];

    protected $appends = [
        'price_string',
        'discount_string',
        'discount_amount_string',
        'subtotal_string'
    ];

	protected $fillable = [
		'header_id',
		'item_id',
		'quantity',
		'price',
		'discount',
		'subtotal',
        'received_quantity',
        'quantity_invoiced',
		'remark'
	];

    public function getPriceStringAttribute(){
        return number_format($this->attributes['price'], 0, ",", ".");
    }

    public function getDiscountStringAttribute(){
        return number_format($this->attributes['discount'], 0, ",", ".");
    }

    public function getDiscountAmountStringAttribute(){
        return number_format($this->attributes['discount'], 0, ",", ".");
    }

    public function getSubtotalStringAttribute(){
        return number_format($this->attributes['subtotal'], 0, ",", ".");
    }

	public function item()
	{
		return $this->belongsTo(\App\Models\Item::class);
	}

	public function purchase_order_header()
	{
		return $this->belongsTo(\App\Models\PurchaseOrderHeader::class, 'header_id');
	}
}
