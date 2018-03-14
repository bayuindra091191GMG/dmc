<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 14 Mar 2018 13:46:36 +0700.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class PurchaseInvoiceDetail
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
 * @property \App\Models\PurchaseInvoiceHeader $purchase_invoice_header
 *
 * @package App\Models
 */
class PurchaseInvoiceDetail extends Eloquent
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

    protected $appends = [
        'price_string',
        'discount_string',
        'subtotal_string'
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

    public function getPriceStringAttribute(){
        return number_format($this->attributes['price'], 0, ",", ".");
    }

    public function getDiscountStringAttribute(){
        if(!empty($this->attributes['discount']) && $this->attributes['discount'] !== 0){
            return $this->attributes['discount']. '%';
        }
        else{
            return '-';
        }
    }

    public function getSubtotalStringAttribute(){
        return number_format($this->attributes['subtotal'], 0, ",", ".");
    }

	public function item()
	{
		return $this->belongsTo(\App\Models\Item::class);
	}

	public function purchase_invoice_header()
	{
		return $this->belongsTo(\App\Models\PurchaseInvoiceHeader::class, 'header_id');
	}
}
