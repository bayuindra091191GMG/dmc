<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 05 Apr 2018 10:00:28 +0700.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class PermissionMenuSub
 * 
 * @property int $id
 * @property int $role_id
 * @property int $menu_sub_id
 * @property \Carbon\Carbon $created_at
 * @property int $created_by
 * @property \Carbon\Carbon $updated_at
 * @property int $updated_by
 * 
 * @property \App\Models\User $user
 * @property \App\Models\MenuSub $menu_sub
 * @property \App\Models\Role $role
 *
 * @package App\Models
 */
class PermissionMenuSub extends Eloquent
{
	protected $casts = [
		'role_id' => 'int',
		'menu_sub_id' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $fillable = [
		'role_id',
		'menu_sub_id',
		'created_by',
		'updated_by'
	];

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class, 'updated_by');
	}

	public function menu_sub()
	{
		return $this->belongsTo(\App\Models\MenuSub::class);
	}

	public function role()
	{
		return $this->belongsTo(\App\Models\Role::class);
	}
}
