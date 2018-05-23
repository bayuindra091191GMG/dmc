<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 23 May 2018 13:55:57 +0700.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class TransactionDetail
 * 
 * @property int $id
 * @property int $header_id
 * @property int $schedule_id
 * @property string $day
 * @property int $meeting_attendeds
 * @property \Carbon\Carbon $class_start_date
 * @property \Carbon\Carbon $class_end_date
 * @property float $price
 * @property float $discount
 * @property float $subtotal
 * @property \Carbon\Carbon $updated_at
 * @property int $updated_by
 * 
 * @property \App\Models\TransactionHeader $transaction_header
 * @property \App\Models\Course $course
 * @property \App\Models\Auth\User\User $user
 *
 * @package App\Models
 */
class TransactionDetail extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'header_id' => 'int',
		'schedule_id' => 'int',
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
		'schedule_id',
		'day',
		'meeting_attendeds',
		'class_start_date',
		'class_end_date',
		'price',
		'discount',
		'subtotal',
		'updated_by'
	];

	public function transaction_header()
	{
		return $this->belongsTo(\App\Models\TransactionHeader::class, 'header_id');
	}

	public function course()
	{
		return $this->belongsTo(\App\Models\Course::class, 'schedule_id');
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
