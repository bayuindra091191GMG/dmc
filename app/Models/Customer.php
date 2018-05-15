<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 15 May 2018 15:30:37 +0700.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Customer
 * 
 * @property int $id
 * @property string $name
 * @property string $username
 * @property string $email
 * @property string $image_profile
 * @property int $status_id
 * @property \Carbon\Carbon $created_at
 * @property int $created_by
 * @property \Carbon\Carbon $updated_at
 * @property int $updated_by
 * 
 * @property \App\Models\Status $status
 * @property \App\Models\User $user
 * @property \Illuminate\Database\Eloquent\Collection $attendances
 * @property \Illuminate\Database\Eloquent\Collection $transaction_headers
 *
 * @package App\Models
 */
class Customer extends Eloquent
{
	protected $casts = [
		'status_id' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $fillable = [
		'name',
		'username',
		'email',
		'image_profile',
		'status_id',
		'created_by',
		'updated_by'
	];

	public function status()
	{
		return $this->belongsTo(\App\Models\Status::class);
	}

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class, 'updated_by');
	}

	public function attendances()
	{
		return $this->hasMany(\App\Models\Attendance::class);
	}

	public function transaction_headers()
	{
		return $this->hasMany(\App\Models\TransactionHeader::class);
	}
}
