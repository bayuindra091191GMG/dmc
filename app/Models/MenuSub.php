<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 05 Apr 2018 09:46:28 +0700.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class MenuSub
 * 
 * @property int $id
 * @property string $name
 * @property string $route
 * @property int $menu_id
 * 
 * @property \App\Models\Menu $menu
 *
 * @package App\Models
 */
class MenuSub extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'menu_id' => 'int'
	];

	protected $fillable = [
		'name',
		'menu_id',
        'route'
	];

	public function menu()
	{
		return $this->belongsTo(\App\Models\Menu::class);
	}
}
