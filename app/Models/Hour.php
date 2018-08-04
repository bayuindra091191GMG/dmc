<?php

/**
 * Created by Reliese Model.
 * Date: Sat, 04 Aug 2018 12:49:04 +0700.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Hour
 * 
 * @property int $id
 * @property int $day_id
 * @property string $hour_string
 * 
 * @property \App\Models\Day $day
 *
 * @package App\Models
 */
class Hour extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'day_id' => 'int'
	];

	protected $fillable = [
		'day_id',
		'hour_string'
	];

	public function day()
	{
		return $this->belongsTo(\App\Models\Day::class);
	}
}
