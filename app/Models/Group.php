<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 13 Feb 2018 03:33:21 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Group
 * 
 * @property int $id
 * @property string $code
 * @property string $name
 * 
 * @property \Illuminate\Database\Eloquent\Collection $items
 *
 * @package App\Models
 */
class Group extends Eloquent
{
	public $timestamps = false;

	protected $fillable = [
		'code',
		'name'
	];

	public function items()
	{
		return $this->hasMany(\App\Models\Item::class);
	}
}
