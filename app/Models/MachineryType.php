<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 01 Feb 2018 07:57:22 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class MachineryType
 * 
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $description
 * 
 * @property \Illuminate\Database\Eloquent\Collection $machineries
 *
 * @package App\Models
 */
class MachineryType extends Eloquent
{
	public $timestamps = false;

	protected $fillable = [
		'code',
		'name',
		'description'
	];

	public function machineries()
	{
		return $this->hasMany(\App\Models\Machinery::class, 'type_id');
	}
}
