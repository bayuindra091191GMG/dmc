<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 23 Feb 2018 10:28:21 +0700.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ItemStock
 * 
 * @property int $id
 * @property int $item_id
 * @property int $warehouse_id
 * @property int $stock
 * @property int $created_by
 * @property \Carbon\Carbon $created_at
 * @property int $updated_by
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\Item $item
 * @property \App\Models\Warehouse $warehouse
 * @property \App\Models\Auth\User\User $user
 *
 * @package App\Models
 */
class ItemStock extends Eloquent
{
	protected $casts = [
		'item_id' => 'int',
		'warehouse_id' => 'int',
		'stock' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $fillable = [
		'item_id',
		'warehouse_id',
		'stock',
		'created_by',
		'updated_by'
	];

	public function item()
	{
		return $this->belongsTo(\App\Models\Item::class);
	}

	public function warehouse()
	{
		return $this->belongsTo(\App\Models\Warehouse::class);
	}

    public function createdBy()
    {
        return $this->belongsTo(\App\Models\Auth\User\User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(\App\Models\Auth\User\User::class, 'updated_by');
    }
}
