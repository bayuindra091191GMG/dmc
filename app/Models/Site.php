<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 19 Jan 2018 06:52:40 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Site
 * 
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $location
 * 
 * @property \Illuminate\Database\Eloquent\Collection $employees
 * @property \Illuminate\Database\Eloquent\Collection $users
 *
 * @package App\Models
 */
class Site extends Eloquent
{
	public $timestamps = false;

	protected $fillable = [
		'code',
		'name',
		'location'
	];

	public function employees()
	{
		return $this->hasMany(\App\Models\Employee::class);
	}

	public function users()
	{
		return $this->hasMany(\App\Models\User::class);
	}
}
