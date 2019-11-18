<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 15 May 2018 15:30:37 +0700.
 */

namespace App\Models;

use Carbon\Carbon;
use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Customer
 * 
 * @property int $id
 * @property string $member_id
 * @property string $barcode
 * @property string $name
 * @property string $username
 * @property string $email
 * @property string $phone
 * @property string $parent_name
 * @property string $age
 * @property string $address
 * @property string $image_profile
 * @property int $point
 * @property int $status_id
 * @property \Carbon\Carbon $created_at
 * @property int $created_by
 * @property \Carbon\Carbon $updated_at
 * @property int $updated_by
 * @property \Carbon\Carbon $dob
 * @property string $photo_path
 * 
 * @property \App\Models\Status $status
 * @property \App\Models\Auth\User\User $user
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
		'updated_by' => 'int',
        'point' => 'int'
	];

	protected $fillable = [
	    'member_id',
        'barcode',
		'name',
		'username',
		'email',
		'image_profile',
        'age',
        'phone',
        'parent_name',
        'address',
		'status_id',
		'created_by',
		'updated_by',
        'dob',
        'photo_path',
        'point'
	];

	protected $appends = [
	    'dob_string'
    ];

    public function getDobStringAttribute(){
        return Carbon::parse($this->attributes['dob'])->format('d M Y');
    }

	public function status()
	{
		return $this->belongsTo(\App\Models\Status::class);
	}

    public function createdBy()
    {
        return $this->belongsTo(\App\Models\Auth\User\User::class, 'created_by');
    }

	public function updatedBy()
	{
		return $this->belongsTo(\App\Models\Auth\User\User::class, 'updated_by');
	}

	public function attendances()
	{
		return $this->hasMany(\App\Models\Attendance::class);
	}

	public function transaction_headers()
	{
		return $this->hasMany(\App\Models\TransactionHeader::class);
	}

    public function customer_point_histories()
    {
        return $this->hasMany(\App\Models\CustomerPointHistory::class);
    }
}
