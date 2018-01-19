<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 19 Jan 2018 04:22:50 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class PurchaseRequestHeader
 * 
 * @property int $id
 * @property string $code
 * @property string $date
 * @property int $department_id
 * @property int $status_id
 * @property int $created_by
 * @property \Carbon\Carbon $created_at
 * @property int $updated_by
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\Department $department
 * @property \App\Models\Status $status
 * @property \App\Models\Auth\User\User $user
 * @property \Illuminate\Database\Eloquent\Collection $approval_purchase_requests
 * @property \Illuminate\Database\Eloquent\Collection $purchase_order_headers
 * @property \Illuminate\Database\Eloquent\Collection $purchase_request_details
 *
 * @package App\Models
 */
class PurchaseRequestHeader extends Eloquent
{
	protected $casts = [
		'department_id' => 'int',
		'status_id' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $fillable = [
		'code',
		'date',
		'department_id',
		'status_id',
		'created_by',
		'updated_by'
	];

	public function department()
	{
		return $this->belongsTo(\App\Models\Department::class);
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

	public function approval_purchase_requests()
	{
		return $this->hasMany(\App\Models\ApprovalPurchaseRequest::class, 'purchase_request_id');
	}

	public function purchase_order_headers()
	{
		return $this->hasMany(\App\Models\PurchaseOrderHeader::class, 'purchasing_request_id');
	}

	public function purchase_request_details()
	{
		return $this->hasMany(\App\Models\PurchaseRequestDetail::class, 'header_id');
	}
}
