<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 20 Mar 2018 11:16:38 +0700.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class MaterialRequestHeader
 * 
 * @property int $id
 * @property string $code
 * @property int $type
 * @property int $department_id
 * @property int $machinery_id
 * @property string $priority
 * @property string $km
 * @property string $hm
 * @property int $status_id
 * @property \Carbon\Carbon $date
 * @property int $created_by
 * @property \Carbon\Carbon $created_at
 * @property int $updated_by
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\Department $department
 * @property \App\Models\Machinery $machinery
 * @property \App\Models\Status $status
 * @property \App\Models\User $user
 * @property \Illuminate\Database\Eloquent\Collection $material_request_details
 *
 * @package App\Models
 */
class MaterialRequestHeader extends Eloquent
{
	protected $casts = [
	    'type' => 'int',
		'department_id' => 'int',
		'machinery_id' => 'int',
		'status_id' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $dates = [
		'date'
	];

	protected $fillable = [
		'code',
        'type',
		'department_id',
		'machinery_id',
		'priority',
		'km',
		'hm',
		'status_id',
		'date',
		'created_by',
		'updated_by'
	];

	public function department()
	{
		return $this->belongsTo(\App\Models\Department::class);
	}

	public function machinery()
	{
		return $this->belongsTo(\App\Models\Machinery::class);
	}

	public function status()
	{
		return $this->belongsTo(\App\Models\Status::class);
	}

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class, 'updated_by');
	}

	public function material_request_details()
	{
		return $this->hasMany(\App\Models\MaterialRequestDetail::class, 'header_id');
	}
}