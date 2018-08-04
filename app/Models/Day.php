<?php

/**
 * Created by Reliese Model.
 * Date: Sat, 04 Aug 2018 12:48:58 +0700.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Day
 * 
 * @property int $id
 * @property int $course_id
 * @property string $day_string
 * 
 * @property \App\Models\Course $course
 * @property \Illuminate\Database\Eloquent\Collection $hours
 *
 * @package App\Models
 */
class Day extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'course_id' => 'int'
	];

	protected $fillable = [
		'course_id',
		'day_string'
	];

	public function course()
	{
		return $this->belongsTo(\App\Models\Course::class);
	}

	public function hours()
	{
		return $this->hasMany(\App\Models\Hour::class);
	}
}
