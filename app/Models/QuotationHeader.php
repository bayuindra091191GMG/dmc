<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 19 Apr 2018 14:10:00 +0700.
 */

namespace App\Models;

use Carbon\Carbon;
use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class QuotationHeader
 * 
 * @property int $id
 * @property string $code
 * @property int $purchase_request_id
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
 * @property int $status_id
 * @property \Carbon\Carbon $date
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
        'total_discount' => 'float',
		'total_price' => 'float',
		'delivery_fee' => 'float',
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
    ];

	protected $dates = [
		'date'
	];

	protected $fillable = [
		'code',
		'purchase_request_id',
		'supplier_id',
        'delivery_fee',
		'total_price',
		'total_discount',
		'total_payment_before_tax',
		'pph_percent',
		'ppn_percent',
		'pph_amount',
		'ppn_amount',
		'total_payment',
		'status_id',
		'date',
		'created_by',
		'updated_by'
	];

    public function getDateStringAttribute(){
        return Carbon::parse($this->attributes['date'])->format('d M Y');
    }

    public function getDeliveryFeeStringAttribute(){
        return number_format($this->attributes['delivery_fee'], 0, ",", ".");
    }

    public function getTotalPriceStringAttribute(){
        return number_format($this->attributes['total_price'], 0, ",", ".");
    }

    public function getTotalDiscountStringAttribute(){
        return number_format($this->attributes['total_discount'], 0, ",", ".");
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
