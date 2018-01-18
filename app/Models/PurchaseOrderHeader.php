<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 18 Jan 2018 07:30:31 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class PurchaseOrderHeader
 * 
 * @property int $id
 * @property string $code
 * @property \Carbon\Carbon $date
 * @property string $quot_no
 * @property \Carbon\Carbon $quot_date
 * @property int $purchasing_request_id
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
 * @property \App\Models\PurchaseRequestHeader $purchase_request_header
 * @property \App\Models\Status $status
 * @property \Illuminate\Database\Eloquent\Collection $payment_requests
 * @property \Illuminate\Database\Eloquent\Collection $purchase_order_details
 *
 * @package App\Models
 */
class PurchaseOrderHeader extends Eloquent
{
	protected $casts = [
		'purchasing_request_id' => 'int',
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
		'date',
		'quot_date'
	];

	protected $fillable = [
		'code',
		'date',
		'quot_no',
		'quot_date',
		'purchasing_request_id',
		'subtotal_price',
		'delivery_charge',
		'pph_ps_23',
		'ppn',
		'total_price',
		'status_id',
		'created_by',
		'updated_by'
	];

	public function purchase_request_header()
	{
		return $this->belongsTo(\App\Models\PurchaseRequestHeader::class, 'purchasing_request_id');
	}

	public function status()
	{
		return $this->belongsTo(\App\Models\Status::class);
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
