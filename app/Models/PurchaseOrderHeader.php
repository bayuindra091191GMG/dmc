<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 19 Feb 2018 03:17:21 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class PurchaseOrderHeader
 * 
 * @property int $id
 * @property string $code
 * @property \Carbon\Carbon $date
 * @property int $quotation_id
 * @property int $supplier_id
 * @property float $subtotal_price
 * @property float $delivery_charge
 * @property float $pph_ps_23
 * @property float $ppn
 * @property float $total_price
 * @property int $status_id
 * @property int $created_by
 * @property \Carbon\Carbon $created_at
 * @property int $updated_by
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\QuotationHeader $quotation_header
 * @property \App\Models\Status $status
 * @property \App\Models\Supplier $supplier
 * @property \App\Models\User $user
 * @property \Illuminate\Database\Eloquent\Collection $item_receipt_details
 * @property \Illuminate\Database\Eloquent\Collection $payment_requests
 * @property \Illuminate\Database\Eloquent\Collection $purchase_order_details
 *
 * @package App\Models
 */
class PurchaseOrderHeader extends Eloquent
{
	protected $casts = [
		'quotation_id' => 'int',
		'supplier_id' => 'int',
		'subtotal_price' => 'float',
		'delivery_charge' => 'float',
		'pph_ps_23' => 'float',
		'ppn' => 'float',
		'total_price' => 'float',
		'status_id' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $dates = [
		'date'
	];

	protected $fillable = [
		'code',
		'date',
		'quotation_id',
		'supplier_id',
		'subtotal_price',
		'delivery_charge',
		'pph_ps_23',
		'ppn',
		'total_price',
		'status_id',
		'created_by',
		'updated_by'
	];

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
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
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
