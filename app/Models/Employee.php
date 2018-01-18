<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 18 Jan 2018 07:30:31 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Employee
 * 
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property \Carbon\Carbon $date_of_birth
 * @property string $address
 * @property \Carbon\Carbon $work_start_date
 * @property \Carbon\Carbon $work_finish_date
 * @property int $department_id
 * @property int $site_id
 * @property float $salary
 * @property int $status_id
 * @property int $created_by
 * @property \Carbon\Carbon $created_at
 * @property int $updated_by
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\Site $site
 * @property \App\Models\Status $status
 * @property \App\Models\User $user
 * @property \App\Models\Department $department
 * @property \Illuminate\Database\Eloquent\Collection $payment_requests
 * @property \Illuminate\Database\Eloquent\Collection $users
 *
 * @package App\Models
 */
class Employee extends Eloquent
{
	protected $casts = [
		'department_id' => 'int',
		'site_id' => 'int',
		'salary' => 'float',
		'status_id' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $dates = [
		'date_of_birth',
		'work_start_date',
		'work_finish_date'
	];

	protected $fillable = [
		'name',
		'email',
		'phone',
		'date_of_birth',
		'address',
		'work_start_date',
		'work_finish_date',
		'department_id',
		'site_id',
		'salary',
		'status_id',
		'created_by',
		'updated_by'
	];

	public function site()
	{
		return $this->belongsTo(\App\Models\Site::class);
	}

	public function status()
	{
		return $this->belongsTo(\App\Models\Status::class);
	}

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class, 'updated_by');
	}

	public function department()
	{
		return $this->belongsTo(\App\Models\Department::class);
	}

	public function payment_requests()
	{
		return $this->hasMany(\App\Models\PaymentRequest::class, 'request_by');
	}

	public function users()
	{
		return $this->hasMany(\App\Models\User::class);
	}
}
