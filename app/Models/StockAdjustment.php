<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 14 Feb 2018 09:31:20 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class StockAdjustment
 * 
 * @property int $id
 * @property int $item_id
 * @property int $depreciation
 * @property int $new_stock
 * @property int $created_by
 * @property \Carbon\Carbon $created_at
 * @property int $updated_by
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\Item $item
 *
 * @package App\Models
 */
class StockAdjustment extends Eloquent
{
	protected $casts = [
		'item_id' => 'int',
		'depreciation' => 'int',
		'new_stock' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $fillable = [
		'item_id',
		'depreciation',
		'new_stock',
		'created_by',
		'updated_by'
	];

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
