<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 05 Apr 2018 09:46:44 +0700.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class MenuHeader
 * 
 * @property int $id
 * @property string $name
 * @property int $index
 * 
 * @property \Illuminate\Database\Eloquent\Collection $menus
 *
 * @package App\Models
 */
class MenuHeader extends Eloquent
{
	public $timestamps = false;

    protected $casts = [
        'index' => 'int'
    ];

	protected $fillable = [
		'name',
        'index'
	];

	public function menus()
	{
		return $this->hasMany(\App\Models\Menu::class);
	}
}
