<?php

/**
 * Created by Reliese Model.
 * Date: Sat, 17 Mar 2018 15:08:46 +0700.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class StockCard
 * 
 * @property int $id
 * @property int $item_id
 * @property int $warehouse_id
 * @property int $change
 * @property int $stock
 * @property int $created_by
 * @property string $flag
 * @property string $description
 * @property \Carbon\Carbon $created_at
 * @property int $updated_by
 * @property \Carbon\Carbon $updated_at
 *
 * @property \App\Models\Auth\User\User $user
 * @property \App\Models\Item $item
 * @property \App\Models\Warehouse $warehouse
 *
 * @package App\Models
 */
class StockCard extends Eloquent
{
	protected $casts = [
		'item_id' => 'int',
		'warehouse_id' => 'int',
		'change' => 'int',
		'stock' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int'
	];

    protected $dates = [
        'created_at'
    ];

	protected $fillable = [
		'item_id',
		'warehouse_id',
		'change',
		'stock',
		'flag',
		'description',
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
