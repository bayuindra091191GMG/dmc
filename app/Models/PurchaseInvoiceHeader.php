<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 14 Mar 2018 13:46:27 +0700.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class PurchaseInvoiceHeader
 * 
 * @property int $id
 * @property string $code
 * @property int $purchase_order_id
 * @property float $delivery_fee
 * @property float $total_discount
 * @property float $total_price
 * @property float $total_payment_before_tax
 * @property int $pph_percent
 * @property int $ppn_percent
 * @property float $pph_amount
 * @property float $ppn_amount
 * @property float $total_payment
 * @property int $status_id
 * @property int $created_by
 * @property \Carbon\Carbon $created_at
 * @property int $updated_by
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\PurchaseOrderHeader $purchase_order_header
 * @property \App\Models\Status $status
 * @property \App\Models\User $user
 * @property \Illuminate\Database\Eloquent\Collection $purchase_invoice_details
 *
 * @package App\Models
 */
class PurchaseInvoiceHeader extends Eloquent
{
	protected $casts = [
		'purchase_order_id' => 'int',
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
    ];

	protected $fillable = [
		'code',
		'purchase_order_id',
		'delivery_fee',
		'total_discount',
		'total_price',
		'total_payment_before_tax',
		'pph_percent',
		'ppn_percent',
		'pph_amount',
		'ppn_amount',
		'total_payment',
		'status_id',
		'created_by',
		'updated_by'
	];

    public function getDateStringAttribute(){
        return Carbon::parse($this->attributes['created_at'])->format('d M Y');
    }

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

    public function getPpnStringAttribute(){
        return 'Rp '. number_format($this->attributes['ppn_amount'], 0, ",", ".");
    }

    public function getPphStringAttribute(){
        return 'Rp '. number_format($this->attributes['pph_amount'], 0, ",", ".");
    }

    public function getTotalPaymentStringAttribute(){
        return 'Rp '. number_format($this->attributes['total_payment'], 0, ",", ".");
    }

    public function getDeliveryFeeStringAttribute(){
        return 'Rp '. number_format($this->attributes['delivery_fee'], 0, ",", ".");
    }

    public function scopeDateDescending(Builder $query){
        return $query->orderBy('created_at','DESC');
    }

	public function purchase_order_header()
	{
		return $this->belongsTo(\App\Models\PurchaseOrderHeader::class, 'purchase_order_id');
	}

	public function status()
	{
		return $this->belongsTo(\App\Models\Status::class);
	}

    public function createdBy()
    {
        return $this->belongsTo(\App\Models\Auth\User\User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(\App\Models\Auth\User\User::class, 'updated_by');
    }

	public function purchase_invoice_details()
	{
		return $this->hasMany(\App\Models\PurchaseInvoiceDetail::class, 'header_id');
	}
}
