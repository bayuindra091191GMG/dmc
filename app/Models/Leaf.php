<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 17 Oct 2018 10:18:30 +0700.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Leaf
 * 
 * @property int $id
 * @property int $transaction_id
 * @property int $schedule_id
 * @property int $month_amount
 * @property \Carbon\Carbon $start_date
 * @property \Carbon\Carbon $end_date
 * @property int $status_id
 * @property \Carbon\Carbon $created_at
 * @property int $created_by
 * @property \Carbon\Carbon $updated_at
 * @property int $updated_by
 * 
 * @property \App\Models\Schedule $schedule
 * @property \App\Models\Status $status
 * @property \App\Models\TransactionHeader $transaction_header
 * @property \App\Models\Auth\User\User $user
 *
 * @package App\Models
 */
class Leaf extends Eloquent
{
	protected $casts = [
		'transaction_id' => 'int',
		'schedule_id' => 'int',
		'month_amount' => 'int',
		'status_id' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $dates = [
		'start_date',
		'end_date'
	];

	protected $fillable = [
		'transaction_id',
		'schedule_id',
		'month_amount',
		'start_date',
		'end_date',
		'status_id',
		'created_by',
		'updated_by'
	];

	public function schedule()
	{
		return $this->belongsTo(\App\Models\Schedule::class);
	}

	public function status()
	{
		return $this->belongsTo(\App\Models\Status::class);
	}

	public function transaction_header()
	{
		return $this->belongsTo(\App\Models\TransactionHeader::class, 'transaction_id');
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
