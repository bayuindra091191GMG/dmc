<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 19 Jan 2018 04:01:54 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Item
 * 
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int $stock
 * @property float $value
 * @property int $is_serial
 * @property int $group_id
 * @property int $warehouse_id
 * @property \Carbon\Carbon $created_date
 * @property string $description
 * @property int $created_by
 * @property \Carbon\Carbon $created_at
 * @property int $updated_by
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\Auth\User\User $user
 * @property \App\Models\Group $group
 * @property \App\Models\Warehouse $warehouse
 * @property \Illuminate\Database\Eloquent\Collection $purchase_order_details
 * @property \Illuminate\Database\Eloquent\Collection $purchase_request_details
 * @property \Illuminate\Database\Eloquent\Collection $serials
 *
 * @package App\Models
 */
class Item extends Eloquent
{
	protected $casts = [
		'stock' => 'int',
		'value' => 'float',
		'is_serial' => 'int',
		'group_id' => 'int',
		'warehouse_id' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $dates = [
		'created_date'
	];

	protected $fillable = [
		'code',
		'name',
		'stock',
		'value',
		'is_serial',
		'group_id',
		'warehouse_id',
		'created_date',
		'description',
		'created_by',
		'updated_by'
	];

	public function group()
	{
		return $this->belongsTo(\App\Models\Group::class);
	}

	public function warehouse()
	{
		return $this->belongsTo(\App\Models\Warehouse::class);
	}

	public function purchase_order_details()
	{
		return $this->hasMany(\App\Models\PurchaseOrderDetail::class);
	}

	public function purchase_request_details()
	{
		return $this->hasMany(\App\Models\PurchaseRequestDetail::class);
	}

	public function serials()
	{
		return $this->hasMany(\App\Models\Serial::class);
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
