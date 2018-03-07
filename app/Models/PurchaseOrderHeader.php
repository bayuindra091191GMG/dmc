<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 22 Feb 2018 16:36:17 +0700.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class PurchaseOrderHeader
 * 
 * @property int $id
 * @property string $code
 * @property \Carbon\Carbon $date
 * @property int $purchase_request_id
 * @property int $quotation_id
 * @property int $supplier_id
 * @property float $pph_ps_23
 * @property float $ppn
 * @property float $delivery_fee
 * @property float $total_discount
 * @property float $total_price
 * @property float $total_payment
 * @property int $status_id
 * @property int $created_by
 * @property \Carbon\Carbon $created_at
 * @property int $updated_by
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\PurchaseRequestHeader $purchase_request_header
 * @property \App\Models\QuotationHeader $quotation_header
 * @property \App\Models\Status $status
 * @property \App\Models\Supplier $supplier
 * @property \App\Models\Auth\User\User $user
 * @property \Illuminate\Database\Eloquent\Collection $item_receipt_details
 * @property \Illuminate\Database\Eloquent\Collection $payment_requests
 * @property \Illuminate\Database\Eloquent\Collection $purchase_order_details
 *
 * @package App\Models
 */
class PurchaseOrderHeader extends Eloquent
{
	protected $casts = [
		'purchase_request_id' => 'int',
		'quotation_id' => 'int',
		'supplier_id' => 'int',
		'pph_ps_23' => 'float',
		'ppn' => 'float',
		'delivery_fee' => 'float',
		'total_discount' => 'float',
		'total_price' => 'float',
		'total_payment' => 'float',
		'status_id' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $dates = [
		'date'
	];

    protected $appends = [
        'total_price_string',
        'total_discount_string',
        'total_payment_string',
        'delivery_fee_string'
    ];

	protected $fillable = [
		'code',
		'date',
		'purchase_request_id',
		'quotation_id',
		'supplier_id',
		'pph_ps_23',
		'ppn',
		'delivery_fee',
		'total_discount',
		'total_price',
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

    public function getDeliveryFeeStringAttribute(){
        if(!empty($this->attributes['total_discount']) && $this->attributes['delivery_fee'] != 0){
            return 'Rp '. number_format($this->attributes['delivery_fee'], 0, ",", ".");
        }
        else{
            return '-';
        }
    }

    public function scopeDateDescending(Builder $query){
        return $query->orderBy('created_at','DESC');
    }

	public function purchase_request_header()
	{
		return $this->belongsTo(\App\Models\PurchaseRequestHeader::class, 'purchase_request_id');
	}

	public function quotation_header()
	{
		return $this->belongsTo(\App\Models\QuotationHeader::class, 'quotation_id');
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

	public function item_receipt_details()
	{
		return $this->hasMany(\App\Models\ItemReceiptDetail::class, 'purchase_order_id');
	}

	public function payment_requests()
	{
		return $this->hasMany(\App\Models\PaymentRequest::class, 'purchase_order_id');
	}

	public function purchase_order_details()
	{
		return $this->hasMany(\App\Models\PurchaseOrderDetail::class, 'header_id');
	}
}
