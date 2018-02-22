<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 19 Feb 2018 03:21:14 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class QuotationHeader
 * 
 * @property int $id
 * @property string $code
 * @property int $purchase_request_id
 * @property int $supplier_id
 * @property float $total_price
 * @property float $total_discount
 * @property float $total_payment
 * @property int $status_id
 * @property int $created_by
 * @property \Carbon\Carbon $created_at
 * @property int $updated_by
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\PurchaseRequestHeader $purchase_request_header
 * @property \App\Models\Status $status
 * @property \App\Models\Supplier $supplier
 * @property \App\Models\Auth\User\User $user
 * @property \Illuminate\Database\Eloquent\Collection $purchase_order_headers
 * @property \Illuminate\Database\Eloquent\Collection $quotation_details
 *
 * @package App\Models
 */
class QuotationHeader extends Eloquent
{
	protected $casts = [
		'purchase_request_id' => 'int',
		'supplier_id' => 'int',
		'total_price' => 'float',
		'total_discount' => 'float',
		'total_payment' => 'float',
		'status_id' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $appends = [
	    'total_price_string',
        'total_discount_string',
        'total_payment_string'
    ];

	protected $fillable = [
		'code',
		'purchase_request_id',
		'supplier_id',
		'total_price',
		'total_discount',
		'total_payment',
		'status_id',
		'created_by',
		'updated_by'
	];

    public function getTotalPriceStringAttribute(){
        return 'Rp '. number_format($this->attributes['total_price'], 0, ",", ".");
    }

    public function getTotalDiscountStringAttribute(){
        if(!empty($this->attributes['total_discount']) && $this->attributes['total_discount'] != 0){
            return 'Rp '. number_format($this->attributes['total_discount'], 0, ",", ".");
        }
        else{
            return '-';
        }
    }

    public function getTotalPaymentStringAttribute(){
        return 'Rp '. number_format($this->attributes['total_payment'], 0, ",", ".");
    }

	public function purchase_request_header()
	{
		return $this->belongsTo(\App\Models\PurchaseRequestHeader::class, 'purchase_request_id');
	}

	public function status()
	{
		return $this->belongsTo(\App\Models\Status::class);
	}

	public function supplier()
	{
		return $this->belongsTo(\App\Models\Supplier::class);
	}

    public function createdBy()
    {
        return $this->belongsTo(\App\Models\Auth\User\User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(\App\Models\Auth\User\User::class, 'updated_by');
    }

	public function purchase_order_headers()
	{
		return $this->hasMany(\App\Models\PurchaseOrderHeader::class, 'quotation_id');
	}

	public function quotation_details()
	{
		return $this->hasMany(\App\Models\QuotationDetail::class, 'header_id');
	}
}
