<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 15 Mar 2018 09:44:35 +0700.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Site
 * 
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $location
 * @property string $phone
 * @property string $pic
 * @property \Carbon\Carbon $created_at
 * @property int $created_by
 * @property \Carbon\Carbon $updated_at
 * @property int $updated_by
 * 
 * @property \App\Models\Auth\User\User $user
 * @property \Illuminate\Database\Eloquent\Collection $delivery_order_headers
 * @property \Illuminate\Database\Eloquent\Collection $employees
 * @property \Illuminate\Database\Eloquent\Collection $warehouses
 *
 * @package App\Models
 */
class Site extends Eloquent
{
	protected $casts = [
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $fillable = [
		'code',
		'name',
		'location',
		'phone',
		'pic',
		'created_by',
		'updated_by'
	];

    public function createdBy()
    {
        return $this->belongsTo(\App\Models\Auth\User\User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(\App\Models\Auth\User\User::class, 'updated_by');
    }

	public function delivery_order_headers()
	{
		return $this->hasMany(\App\Models\DeliveryOrderHeader::class, 'to_site_id');
	}

	public function employees()
	{
		return $this->hasMany(\App\Models\Employee::class);
	}

	public function warehouses()
	{
		return $this->hasMany(\App\Models\Warehouse::class);
	}
}
