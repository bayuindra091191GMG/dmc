<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 18 Jan 2018 07:30:31 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class PaymentInstallment
 * 
 * @property int $id
 * @property int $payment_request_id
 * @property float $amount
 * @property \Carbon\Carbon $payment_date
 * @property string $remark
 * @property int $status_id
 * 
 * @property \App\Models\PaymentRequest $payment_request
 * @property \App\Models\Status $status
 *
 * @package App\Models
 */
class PaymentInstallment extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'payment_request_id' => 'int',
		'amount' => 'float',
		'status_id' => 'int'
	];

	protected $dates = [
		'payment_date'
	];

	protected $fillable = [
		'payment_request_id',
		'amount',
		'payment_date',
		'remark',
		'status_id'
	];

	public function payment_request()
	{
		return $this->belongsTo(\App\Models\PaymentRequest::class);
	}

	public function status()
	{
		return $this->belongsTo(\App\Models\Status::class);
	}
}
