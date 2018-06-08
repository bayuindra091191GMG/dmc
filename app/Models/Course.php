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
 * @property string $hour
 * @property int $valid
 * @property int $status_id
 * @property \Carbon\Carbon $created_at
 * @property int $created_by
 * @property \Carbon\Carbon $updated_at
 * @property int $updated_by
 * @property string $price_string
 * 
 * @property \App\Models\Coach $coach
 * @property \App\Models\Status $status
 * @property \App\Models\Auth\User\User $user
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

    protected $appends = [
        'price_string'
    ];

	protected $fillable = [
		'name',
		'type',
		'coach_id',
		'price',
		'meeting_amount',
        'valid',
        'hour',
		'day',
		'status_id',
		'created_by',
		'updated_by'
	];

    public function getPriceStringAttribute(){
        return number_format($this->attributes['price'], 0, ",", ".");
    }

	public function coach()
	{
		return $this->belongsTo(\App\Models\Coach::class);
	}

	public function status()
	{
		return $this->belongsTo(\App\Models\Status::class);
	}

    public function createdBy()
    {
        return $this->belongsTo(\App\Models\Auth\User\User::class, 'created_by');
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
