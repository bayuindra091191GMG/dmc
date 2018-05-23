<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 23 May 2018 13:55:17 +0700.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Attendance
 * 
 * @property int $id
 * @property int $customer_id
 * @property int $schedule_id
 * @property \Carbon\Carbon $date
 * @property int $meeting_number
 * @property \Carbon\Carbon $created_at
 * @property int $created_by
 * @property \Carbon\Carbon $updated_at
 * @property int $updated_by
 * 
 * @property \App\Models\Customer $customer
 * @property \App\Models\Schedule $schedule
 * @property \App\Models\Auth\User\User $user
 *
 * @package App\Models
 */
class Attendance extends Eloquent
{
	protected $casts = [
		'customer_id' => 'int',
		'schedule_id' => 'int',
		'meeting_number' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $dates = [
		'date'
	];

	protected $fillable = [
		'customer_id',
		'schedule_id',
		'date',
		'meeting_number',
		'created_by',
		'updated_by'
	];

	public function customer()
	{
		return $this->belongsTo(\App\Models\Customer::class);
	}

	public function schedule()
	{
		return $this->belongsTo(\App\Models\Schedule::class);
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
