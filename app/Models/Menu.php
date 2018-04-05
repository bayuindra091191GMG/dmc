<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 05 Apr 2018 09:46:19 +0700.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Menu
 * 
 * @property int $id
 * @property int $menu_header_id
 * @property string $name
 * @property string $route
 * 
 * @property \App\Models\MenuHeader $menu_header
 * @property \Illuminate\Database\Eloquent\Collection $menu_subs
 * @property \Illuminate\Database\Eloquent\Collection $permission_menus
 *
 * @package App\Models
 */
class Menu extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'menu_header_id' => 'int'
	];

	protected $fillable = [
		'menu_header_id',
		'name',
        'route'
	];

	public function menu_header()
	{
		return $this->belongsTo(\App\Models\MenuHeader::class);
	}

	public function menu_subs()
	{
		return $this->hasMany(\App\Models\MenuSub::class);
	}

	public function permission_menus()
	{
		return $this->hasMany(\App\Models\PermissionMenu::class);
	}
}
