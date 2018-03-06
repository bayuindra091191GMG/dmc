<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 18 Jan 2018 07:30:31 +0000.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Department
 * 
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int $created_by
 * @property \Carbon\Carbon $created_at
 * @property int $updated_by
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\User $user
 * @property \Illuminate\Database\Eloquent\Collection $employees
 * @property \Illuminate\Database\Eloquent\Collection $purchase_request_headers
 *
 * @package App\Models
 */
class Department extends Eloquent
{
	protected $casts = [
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $fillable = [
		'code',
		'name',
		'created_by',
		'updated_by'
	];

    public function scopeCodeAscending(Builder $query){
        return $query->orderBy('code','ASC');
    }

    public function createdBy()
    {
        return $this->belongsTo(\App\Models\Auth\User\User::class, 'created_by');
    }

	public function updatedBy()
	{
		return $this->belongsTo(\App\Models\Auth\User\User::class, 'updated_by');
	}

	public function employees()
	{
		return $this->hasMany(\App\Models\Employee::class);
	}

	public function purchase_request_headers()
	{
		return $this->hasMany(\App\Models\PurchaseRequestHeader::class);
	}
}
