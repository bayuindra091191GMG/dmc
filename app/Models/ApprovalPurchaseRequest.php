<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 18 Jan 2018 07:30:31 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ApprovalPurchaseRequest
 * 
 * @property int $id
 * @property int $purchase_request_header_id
 * @property int $approval_user_id
 * @property \Carbon\Carbon $created_at
 * @property int $created_by
 * @property \Carbon\Carbon $updated_at
 * @property int $updated_by
 *
 * @package App\Models
 */
class ApprovalPurchaseRequest extends Eloquent
{
	protected $casts = [
		'purchase_request_header_id' => 'int',
		'approval_user_id' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $fillable = [
		'purchase_request_header_id',
		'approval_user_id',
		'created_by',
		'updated_by'
	];
}
