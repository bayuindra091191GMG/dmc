<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 14 Feb 2018 09:31:52 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class StockIn
 * 
 * @property int $id
 * @property int $item_id
 * @property int $increase
 * @property int $new_stock
 * @property int $created_by
 * @property \Carbon\Carbon $created_at
 * @property int $updated_by
 * @property \Carbon\Carbon $updated_at
 *
 * @property \App\Models\Auth\User\User $user
 * @property \App\Models\Item $item
 *
 * @package App\Models
 */
class StockIn extends Eloquent
{
	protected $casts = [
		'item_id' => 'int',
		'increase' => 'int',
		'new_stock' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int'
	];
    protected $dates = [
        'created_at'
    ];

	protected $fillable = [
		'item_id',
		'increase',
		'new_stock',
		'created_by',
        'created_at'
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
