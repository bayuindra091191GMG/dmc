<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 27 Jun 2019 16:05:58 +0700.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class CourseDetail
 * 
 * @property int $id
 * @property int $course_id
 * @property int $day_number
 * @property string $day_name
 * @property string $time
 * @property int $max_capacity
 * @property int $current_capacity
 * @property int $status_id
 * 
 * @property \App\Models\Course $course
 * @property \App\Models\Status $status
 *
 * @package App\Models
 */
class CourseDetail extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'course_id' => 'int',
		'day_number' => 'int',
		'max_capacity' => 'int',
		'current_capacity' => 'int',
		'status_id' => 'int'
	];

	protected $fillable = [
		'course_id',
		'day_number',
		'day_name',
		'time',
		'max_capacity',
		'current_capacity',
		'status_id'
	];

	public function course()
	{
		return $this->belongsTo(\App\Models\Course::class);
	}

	public function status()
	{
		return $this->belongsTo(\App\Models\Status::class);
	}
}
