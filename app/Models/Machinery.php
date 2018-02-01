<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 01 Feb 2018 03:36:47 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Machinery
 * 
 * @property int $id
 * @property string $code
 * @property int $category_id
 * @property int $brand_id
 * @property int $type_id
 * @property string $description
 * @property int $status_id
 * @property int $created_by
 * @property \Carbon\Carbon $created_at
 * @property int $updated_by
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\User $user
 * @property \App\Models\MachineryBrand $machinery_brand
 * @property \App\Models\MachineryCategory $machinery_category
 * @property \App\Models\MachineryType $machinery_type
 * @property \App\Models\Status $status
 * @property \Illuminate\Database\Eloquent\Collection $serials
 *
 * @package App\Models
 */
class Machinery extends Eloquent
{
	protected $casts = [
		'category_id' => 'int',
		'brand_id' => 'int',
		'type_id' => 'int',
		'status_id' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $fillable = [
		'code',
		'category_id',
		'brand_id',
		'type_id',
		'description',
		'status_id',
		'created_by',
		'updated_by'
	];

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class, 'updated_by');
	}

	public function machinery_brand()
	{
		return $this->belongsTo(\App\Models\MachineryBrand::class, 'brand_id');
	}

	public function machinery_category()
	{
		return $this->belongsTo(\App\Models\MachineryCategory::class, 'category_id');
	}

	public function machinery_type()
	{
		return $this->belongsTo(\App\Models\MachineryType::class, 'type_id');
	}

	public function status()
	{
		return $this->belongsTo(\App\Models\Status::class);
	}

	public function serials()
	{
		return $this->hasMany(\App\Models\Serial::class);
	}
}
