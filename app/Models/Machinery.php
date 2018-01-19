<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 18 Jan 2018 07:30:31 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Machinery
 * 
 * @property int $id
 * @property string $code
 * @property int $marchinery_type_id
 * @property int $created_by
 * @property \Carbon\Carbon $created_at
 * @property int $updated_by
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\Auth\User\User $user
 * @property \App\Models\MachineryType $machinery_type
 * @property \Illuminate\Database\Eloquent\Collection $serials
 *
 * @package App\Models
 */
class Machinery extends Eloquent
{
	protected $casts = [
		'marchinery_type_id' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $fillable = [
		'code',
		'marchinery_type_id',
		'created_by',
		'updated_by'
	];

    public function createdBy()
    {
        return $this->belongsTo(\App\Models\Auth\User\User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(\App\Models\Auth\User\User::class, 'updated_by');
    }

	public function machinery_type()
	{
		return $this->belongsTo(\App\Models\MachineryType::class, 'marchinery_type_id');
	}

	public function serials()
	{
		return $this->hasMany(\App\Models\Serial::class);
	}
}
