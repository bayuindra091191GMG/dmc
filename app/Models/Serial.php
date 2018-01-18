<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 18 Jan 2018 07:30:31 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Serial
 * 
 * @property int $id
 * @property int $item_id
 * @property string $serial_number
 * @property int $warehouse_id
 * @property int $machinery_id
 * @property string $description
 * @property int $created_by
 * @property \Carbon\Carbon $created_at
 * @property int $updated_by
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\User $user
 * @property \App\Models\Item $item
 * @property \App\Models\Machinery $machinery
 * @property \App\Models\Warehouse $warehouse
 *
 * @package App\Models
 */
class Serial extends Eloquent
{
	protected $casts = [
		'item_id' => 'int',
		'warehouse_id' => 'int',
		'machinery_id' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $fillable = [
		'item_id',
		'serial_number',
		'warehouse_id',
		'machinery_id',
		'description',
		'created_by',
		'updated_by'
	];

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class, 'updated_by');
	}

	public function item()
	{
		return $this->belongsTo(\App\Models\Item::class);
	}

	public function machinery()
	{
		return $this->belongsTo(\App\Models\Machinery::class);
	}

	public function warehouse()
	{
		return $this->belongsTo(\App\Models\Warehouse::class);
	}
}
