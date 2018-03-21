<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 19 Mar 2018 14:38:42 +0700.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class PaymentRequestsPiDetail
 * 
 * @property int $id
 * @property int $payment_requests_id
 * @property int $purchase_invoice_header_id
 * 
 * @property \App\Models\PaymentRequest $payment_request
 * @property \App\Models\PurchaseInvoiceHeader $purchase_invoice_header
 *
 * @package App\Models
 */
class PaymentRequestsPiDetail extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'payment_requests_id' => 'int',
		'purchase_invoice_header_id' => 'int'
	];

	protected $fillable = [
		'payment_requests_id',
		'purchase_invoice_header_id'
	];

	public function payment_request()
	{
		return $this->belongsTo(\App\Models\PaymentRequest::class, 'payment_requests_id');
	}

	public function purchase_invoice_header()
	{
		return $this->belongsTo(\App\Models\PurchaseInvoiceHeader::class, 'purchase_invoice_header_id');
	}
}
