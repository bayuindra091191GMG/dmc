<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Nov 2019 14:48:27 +0700.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Voucher
 * 
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $type
 * @property int $point_needed
 * @property int $discount_percentage
 * @property int $discount_total
 * @property int $free_package
 * @property \Carbon\Carbon $created_at
 * @property int $created_by
 * @property \Carbon\Carbon $updated_at
 * @property int $updated_by
 * @property int $status_id
 * 
 * @property \App\Models\User $user
 * @property \App\Models\Status $status
 * @property \Illuminate\Database\Eloquent\Collection $customers
 *
 * @package App\Models
 */
class Voucher extends Eloquent
{
	protected $casts = [
		'point_needed' => 'int',
		'discount_percentage' => 'int',
		'discount_total' => 'int',
		'free_package' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int',
		'status_id' => 'int'
	];

	protected $fillable = [
		'name',
		'description',
		'type',
		'point_needed',
		'discount_percentage',
		'discount_total',
		'free_package',
		'created_by',
		'updated_by',
		'status_id'
	];

    public function createdBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
    }

	public function status()
	{
		return $this->belongsTo(\App\Models\Status::class);
	}

	public function customers()
	{
		return $this->belongsToMany(\App\Models\Customer::class, 'customer_vouchers')
					->withPivot('id', 'quantity', 'status_id');
	}
}
