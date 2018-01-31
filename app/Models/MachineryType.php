<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 31 Jan 2018 07:14:44 +0000.
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
		return $this->hasMany(\App\Models\Machinery::class, 'marchinery_type_id');
	}
}
