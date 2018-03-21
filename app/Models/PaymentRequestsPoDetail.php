<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 19 Mar 2018 14:35:26 +0700.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class PaymentRequestsPoDetail
 * 
 * @property int $id
 * @property int $payment_requests_id
 * @property int $purchase_order_id
 * 
 * @property \App\Models\PaymentRequest $payment_request
 * @property \App\Models\PurchaseOrderHeader $purchase_order_header
 *
 * @package App\Models
 */
class PaymentRequestsPoDetail extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'payment_requests_id' => 'int',
		'purchase_order_id' => 'int'
	];

	protected $fillable = [
		'payment_requests_id',
		'purchase_order_id'
	];

	public function payment_request()
	{
		return $this->belongsTo(\App\Models\PaymentRequest::class, 'payment_requests_id');
	}

	public function purchase_order_header()
	{
		return $this->belongsTo(\App\Models\PurchaseOrderHeader::class, 'purchase_order_id');
	}
}
