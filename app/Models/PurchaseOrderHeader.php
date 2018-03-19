<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 13 Mar 2018 10:12:54 +0700.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class PurchaseOrderHeader
 * 
 * @property int $id
 * @property string $code
 * @property int $purchase_request_id
 * @property int $quotation_id
 * @property int $supplier_id
 * @property float $delivery_fee
 * @property float $total_price
 * @property float $total_discount
 * @property float $total_payment_before_tax
 * @property int $pph_percent
 * @property int $ppn_percent
 * @property float $pph_amount
 * @property float $ppn_amount
 * @property float $total_payment
 * @property \Carbon\Carbon $closing_date
 * @property int $status_id
 * @property \Carbon\Carbon $date
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
		'delivery_fee' => 'float',
		'total_discount' => 'float',
		'total_price' => 'float',
        'total_payment_before_tax' => 'float',
		'pph_percent' => 'int',
		'ppn_percent' => 'int',
		'pph_amount' => 'float',
		'ppn_amount' => 'float',
		'total_payment' => 'float',
		'status_id' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int'
	];

    protected $appends = [
        'total_price_string',
        'total_discount_string',
        'ppn_string',
        'pph_string',
        'total_payment_string',
        'delivery_fee_string',
        'date_string',
        'closing_date_string'
    ];

	protected $fillable = [
		'code',
		'purchase_request_id',
		'quotation_id',
		'supplier_id',
		'delivery_fee',
		'total_discount',
		'total_price',
        'total_payment_before_tax',
		'pph_percent',
		'ppn_percent',
		'pph_amount',
		'ppn_amount',
		'total_payment',
        'closing_date',
		'status_id',
        'date',
		'created_by',
		'updated_by'
	];

    public function getClosingDateStringAttribute(){
        return Carbon::parse($this->attributes['closing_date'])->format('d M Y');
    }

    public function getDateStringAttribute(){
        return Carbon::parse($this->attributes['created_at'])->format('d M Y');
    }

    public function getTotalPriceStringAttribute(){
        return number_format($this->attributes['total_price'], 0, ",", ".");
    }

    public function getTotalDiscountStringAttribute(){
        if(!empty($this->attributes['total_discount']) && $this->attributes['total_discount'] != 0){
            return number_format($this->attributes['total_discount'], 0, ",", ".");
        }
        else{
            return '-';
        }
    }

    public function getPpnStringAttribute(){
        return number_format($this->attributes['ppn_amount'], 0, ",", ".");
    }

    public function getPphStringAttribute(){
        return number_format($this->attributes['pph_amount'], 0, ",", ".");
    }

    public function getTotalPaymentStringAttribute(){
        return number_format($this->attributes['total_payment'], 0, ",", ".");
    }

    public function getDeliveryFeeStringAttribute(){
        return number_format($this->attributes['delivery_fee'], 0, ",", ".");
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
