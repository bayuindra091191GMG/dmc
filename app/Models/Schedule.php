<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 22 May 2018 15:12:28 +0700.
 */

namespace App\Models;

use Carbon\Carbon;
use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Schedule
 * 
 * @property int $id
 * @property int $customer_id
 * @property int $course_id
 * @property string $day
 * @property \Carbon\Carbon $start_date
 * @property \Carbon\Carbon $finish_date
 * @property int $meeting_amount
 * @property int $month_amount
 * @property int $created_by
 * @property \Carbon\Carbon $created_at
 * @property int $updated_by
 * @property \Carbon\Carbon $updated_at
 * @property int $status_id
 * 
 * @property \App\Models\User $user
 * @property \App\Models\Customer $customer
 * @property \App\Models\Course $course
 * @property \App\Models\Status $status
 *
 * @package App\Models
 */
class Schedule extends Eloquent
{
	protected $casts = [
		'customer_id' => 'int',
		'course_id' => 'int',
		'meeting_amount' => 'int',
		'month_amount' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int',
		'status_id' => 'int'
	];

    protected $appends = [
        'start_date_string',
        'finish_date_string'
    ];

	protected $dates = [
		'start_date',
		'finish_date'
	];

	protected $fillable = [
		'customer_id',
		'course_id',
        'day',
		'start_date',
		'finish_date',
		'meeting_amount',
		'month_amount',
		'created_by',
		'updated_by',
		'status_id'
	];

    public function getStartDateStringAttribute(){
        return Carbon::parse($this->attributes['start_date'])->format('d M Y');
    }

    public function getFinishDateStringAttribute(){
        return Carbon::parse($this->attributes['finish_date'])->format('d M Y');
    }

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class, 'updated_by');
	}

	public function customer()
	{
		return $this->belongsTo(\App\Models\Customer::class);
	}

	public function course()
	{
		return $this->belongsTo(\App\Models\Course::class);
	}

	public function status()
	{
		return $this->belongsTo(\App\Models\Status::class);
	}
}
