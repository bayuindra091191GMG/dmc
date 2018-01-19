<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 19 Jan 2018 03:10:16 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ApprovalPurchaseRequest
 * 
 * @property int $id
 * @property int $purchase_request_id
 * @property int $user_id
 * @property \Carbon\Carbon $created_at
 * @property int $created_by
 * @property \Carbon\Carbon $updated_at
 * @property int $updated_by
 * 
 * @property \App\Models\PurchaseRequestHeader $purchase_request_header
 * @property \App\Models\Auth\User\User $user
 *
 * @package App\Models
 */
class ApprovalPurchaseRequest extends Eloquent
{
	protected $casts = [
		'purchase_request_id' => 'int',
		'user_id' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $fillable = [
		'purchase_request_id',
		'user_id',
		'created_by',
		'updated_by'
	];

	public function purchase_request_header()
	{
		return $this->belongsTo(\App\Models\PurchaseRequestHeader::class, 'purchase_request_id');
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
