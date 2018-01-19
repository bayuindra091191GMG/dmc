<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 19 Jan 2018 04:05:33 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Menu
 * 
 * @property int $id
 * @property string $name
 * @property string $description
 *
 * @package App\Models
 */
class Menu extends Eloquent
{
	public $timestamps = false;

	protected $fillable = [
		'name',
		'description'
	];
}
