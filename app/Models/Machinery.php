<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 31 Jan 2018 07:15:55 +0000.
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
 * @property int $marchinery_type_id
 * @property int $created_by
 * @property \Carbon\Carbon $created_at
 * @property int $updated_by
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\User $user
 * @property \App\Models\MachineryBrand $machinery_brand
 * @property \App\Models\MachineryCategory $machinery_category
 * @property \App\Models\MachineryType $machinery_type
 * @property \Illuminate\Database\Eloquent\Collection $serials
 *
 * @package App\Models
 */
class Machinery extends Eloquent
{
	protected $casts = [
		'category_id' => 'int',
		'brand_id' => 'int',
		'marchinery_type_id' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $fillable = [
		'code',
		'category_id',
		'brand_id',
		'marchinery_type_id',
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
		return $this->belongsTo(\App\Models\MachineryType::class, 'marchinery_type_id');
	}

	public function serials()
	{
		return $this->hasMany(\App\Models\Serial::class);
	}
}
