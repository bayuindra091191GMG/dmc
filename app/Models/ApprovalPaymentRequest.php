<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 19 Jan 2018 03:19:38 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ApprovalPaymentRequest
 * 
 * @property int $id
 * @property int $payment_request_id
 * @property int $user_id
 * @property \Carbon\Carbon $created_at
 * @property int $created_by
 * @property \Carbon\Carbon $updated_at
 * @property int $updated_by
 * 
 * @property \App\Models\PaymentRequest $payment_request
 * @property \App\Models\Auth\User\User $user
 *
 * @package App\Models
 */
class ApprovalPaymentRequest extends Eloquent
{
	protected $casts = [
		'payment_request_id' => 'int',
		'user_id' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $fillable = [
		'payment_request_id',
		'user_id',
		'created_by',
		'updated_by'
	];

	public function payment_request()
	{
		return $this->belongsTo(\App\Models\PaymentRequest::class);
	}

	public function user()
	{
		return $this->belongsTo(\App\Models\Auth\User\User::class, 'user_id');
	}

    public function createdBy()
    {
        return $this->belongsTo(\App\Models\Auth\User\User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(\App\Models\Auth\User\User::class, 'updated_by');
    }
}
