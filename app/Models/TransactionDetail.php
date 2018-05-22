<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 21 May 2018 16:00:18 +0700.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class TransactionDetail
 * 
 * @property int $id
 * @property int $header_id
 * @property int $class_id
 * @property string $day
 * @property int $meeting_amounts
 * @property int $meeting_attendeds
 * @property \Carbon\Carbon $class_start_date
 * @property \Carbon\Carbon $class_end_date
 * @property float $price
 * @property float $discount
 * @property float $subtotal
 * @property \Carbon\Carbon $updated_at
 * @property int $updated_by
 * 
 * @property \App\Models\Course $course
 * @property \App\Models\TransactionHeader $transaction_header
 * @property \App\Models\User $user
 * @property \Illuminate\Database\Eloquent\Collection $attendances
 *
 * @package App\Models
 */
class TransactionDetail extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'header_id' => 'int',
		'class_id' => 'int',
		'meeting_amounts' => 'int',
		'meeting_attendeds' => 'int',
		'price' => 'float',
		'discount' => 'float',
		'subtotal' => 'float',
		'updated_by' => 'int'
	];

	protected $dates = [
		'class_start_date',
		'class_end_date'
	];

	protected $fillable = [
		'header_id',
		'class_id',
		'day',
		'meeting_amounts',
		'meeting_attendeds',
		'class_start_date',
		'class_end_date',
		'price',
		'discount',
		'subtotal',
		'updated_by'
	];

	public function course()
	{
		return $this->belongsTo(\App\Models\Course::class, 'class_id');
	}

	public function transaction_header()
	{
		return $this->belongsTo(\App\Models\TransactionHeader::class, 'header_id');
	}

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class, 'updated_by');
	}

	public function attendances()
	{
		return $this->hasMany(\App\Models\Attendance::class);
	}
}