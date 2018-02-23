<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 23 Feb 2018 10:28:00 +0700.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ItemMutation
 * 
 * @property int $id
 * @property int $item_id
 * @property int $from_warehouse_id
 * @property int $to_warehouse_id
 * @property int $mutation_quantity
 * @property int $created_by
 * @property \Carbon\Carbon $created_at
 * @property int $updated_by
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\Warehouse $warehouse_from
 * @property \App\Models\Warehouse $warehouse_to
 * @property \App\Models\Item $item
 * @property \App\Models\Auth\User\User $user
 *
 * @package App\Models
 */
class ItemMutation extends Eloquent
{
	protected $casts = [
		'item_id' => 'int',
		'from_warehouse_id' => 'int',
		'to_warehouse_id' => 'int',
		'mutation_quantity' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $fillable = [
		'item_id',
		'from_warehouse_id',
		'to_warehouse_id',
		'mutation_quantity',
		'created_by',
		'updated_by'
	];

	public function warehouseFrom()
	{
		return $this->belongsTo(\App\Models\Warehouse::class, 'from_warehouse_id');
	}

	public function warehouseTo()
	{
		return $this->belongsTo(\App\Models\Warehouse::class, 'to_warehouse_id');
	}

	public function item()
	{
		return $this->belongsTo(\App\Models\Item::class);
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
