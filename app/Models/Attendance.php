<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 15 May 2018 15:29:38 +0700.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Attendance
 * 
 * @property int $id
 * @property int $class_id
 * @property int $customer_id
 * @property int $transaction_detail_id
 * @property \Carbon\Carbon $date
 * @property int $meeting_number
 * @property \Carbon\Carbon $created_at
 * @property int $created_by
 * @property \Carbon\Carbon $updated_at
 * @property int $updated_by
 * 
 * @property \App\Models\Class $class
 * @property \App\Models\Customer $customer
 * @property \App\Models\TransactionDetail $transaction_detail
 * @property \App\Models\User $user
 *
 * @package App\Models
 */
class Attendance extends Eloquent
{
	protected $casts = [
		'class_id' => 'int',
		'customer_id' => 'int',
		'transaction_detail_id' => 'int',
		'meeting_number' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $dates = [
		'date'
	];

	protected $fillable = [
		'class_id',
		'customer_id',
		'transaction_detail_id',
		'date',
		'meeting_number',
		'created_by',
		'updated_by'
	];

	public function class()
	{
		return $this->belongsTo(\App\Models\Class::class);
	}

	public function customer()
	{
		return $this->belongsTo(\App\Models\Customer::class);
	}

	public function transaction_detail()
	{
		return $this->belongsTo(\App\Models\TransactionDetail::class);
	}

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class, 'updated_by');
	}
}
