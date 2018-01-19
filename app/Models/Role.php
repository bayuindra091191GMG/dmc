<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 19 Jan 2018 04:30:53 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Role
 * 
 * @property int $id
 * @property string $name
 * @property int $weight
 * @property string $description
 *
 * @package App\Models
 */
class Role extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'weight' => 'int'
	];

	protected $fillable = [
		'name',
		'weight',
		'description'
	];
}
