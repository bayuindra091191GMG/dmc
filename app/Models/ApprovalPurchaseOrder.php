<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 19 Apr 2018 11:20:20 +0700.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ApprovalPurchaseOrder
 * 
 * @property int $id
 * @property int $purchase_order_id
 * @property int $user_id
 * @property \Carbon\Carbon $created_at
 * @property int $created_by
 * @property \Carbon\Carbon $updated_at
 * @property int $updated_by
 * 
 * @property \App\Models\User $user
 * @property \App\Models\PurchaseOrderHeader $purchase_order_header
 *
 * @package App\Models
 */
class ApprovalPurchaseOrder extends Eloquent
{
	protected $casts = [
		'purchase_order_id' => 'int',
		'user_id' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $fillable = [
		'purchase_order_id',
		'user_id',
		'created_by',
		'updated_by'
	];

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

	public function purchase_order_header()
	{
		return $this->belongsTo(\App\Models\PurchaseOrderHeader::class, 'purchase_order_id');
	}
}
