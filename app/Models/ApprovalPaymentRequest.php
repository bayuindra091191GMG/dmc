<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 18 Jan 2018 07:30:31 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ApprovalPaymentRequest
 * 
 * @property int $id
 * @property int $payment_request_Id
 * @property int $approval_user_id
 * @property \Carbon\Carbon $created_at
 * @property int $created_by
 * @property \Carbon\Carbon $updated_at
 * @property int $updated_by
 *
 * @package App\Models
 */
class ApprovalPaymentRequest extends Eloquent
{
	protected $casts = [
		'payment_request_Id' => 'int',
		'approval_user_id' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $fillable = [
		'payment_request_Id',
		'approval_user_id',
		'created_by',
		'updated_by'
	];
}
