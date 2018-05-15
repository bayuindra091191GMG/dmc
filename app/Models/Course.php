<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 15 May 2018 15:32:27 +0700.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Course
 * 
 * @property int $id
 * @property string $name
 * @property int $type
 * @property int $coach_id
 * @property float $price
 * @property int $meeting_amount
 * @property string $day
 * @property int $status_id
 * @property \Carbon\Carbon $created_at
 * @property int $created_by
 * @property \Carbon\Carbon $updated_at
 * @property int $updated_by
 * 
 * @property \App\Models\Coach $coach
 * @property \App\Models\Status $status
 * @property \App\Models\User $user
 * @property \Illuminate\Database\Eloquent\Collection $attendances
 * @property \Illuminate\Database\Eloquent\Collection $transaction_details
 *
 * @package App\Models
 */
class Course extends Eloquent
{
	protected $casts = [
		'type' => 'int',
		'coach_id' => 'int',
		'price' => 'float',
		'meeting_amount' => 'int',
		'status_id' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $fillable = [
		'name',
		'type',
		'coach_id',
		'price',
		'meeting_amount',
		'day',
		'status_id',
		'created_by',
		'updated_by'
	];

	public function coach()
	{
		return $this->belongsTo(\App\Models\Coach::class);
	}

	public function status()
	{
		return $this->belongsTo(\App\Models\Status::class);
	}

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class, 'updated_by');
	}

	public function attendances()
	{
		return $this->hasMany(\App\Models\Attendance::class, 'class_id');
	}

	public function transaction_details()
	{
		return $this->hasMany(\App\Models\TransactionDetail::class, 'class_id');
	}
}
