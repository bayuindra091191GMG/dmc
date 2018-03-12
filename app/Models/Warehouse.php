<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 12 Mar 2018 15:14:13 +0700.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Warehouse
 * 
 * @property int $id
 * @property string $code
 * @property string $name
 * 
 * @property \Illuminate\Database\Eloquent\Collection $item_mutations
 * @property \Illuminate\Database\Eloquent\Collection $item_stocks
 * @property \Illuminate\Database\Eloquent\Collection $items
 * @property \Illuminate\Database\Eloquent\Collection $serials
 * @property \Illuminate\Database\Eloquent\Collection $sites
 *
 * @package App\Models
 */
class Warehouse extends Eloquent
{
	public $timestamps = false;

	protected $fillable = [
		'code',
		'name'
	];

	public function item_mutations()
	{
		return $this->hasMany(\App\Models\ItemMutation::class, 'to_warehouse_id');
	}

	public function item_stocks()
	{
		return $this->hasMany(\App\Models\ItemStock::class);
	}

	public function items()
	{
		return $this->hasMany(\App\Models\Item::class);
	}

	public function serials()
	{
		return $this->hasMany(\App\Models\Serial::class);
	}

	public function sites()
	{
		return $this->hasMany(\App\Models\Site::class);
	}
}
