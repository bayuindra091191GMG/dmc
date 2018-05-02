<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 02 May 2018 10:48:50 +0700.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ItemStockNotification
 * 
 * @property int $id
 * @property int $item_id
 * @property \Carbon\Carbon $created_at
 * @property int $created_by
 * 
 * @property \App\Models\Item $item
 * @property \App\Models\Auth\User\User $user
 *
 * @package App\Models
 */
class ItemStockNotification extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'item_id' => 'int',
		'created_by' => 'int'
	];

	protected $fillable = [
		'item_id',
		'created_by'
	];

	public function item()
	{
		return $this->belongsTo(\App\Models\Item::class);
	}

	public function user()
	{
		return $this->belongsTo(\App\Models\Auth\User\User::class, 'created_by');
	}
}
