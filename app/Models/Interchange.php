<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 23 Feb 2018 14:39:20 +0700.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Interchange
 * 
 * @property int $id
 * @property int $item_id_before
 * @property int $item_id_after
 * @property int $created_by
 * @property \Carbon\Carbon $created_at
 * 
 * @property \App\Models\User $user
 * @property \App\Models\Item $item
 *
 * @package App\Models
 */
class Interchange extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'item_id_before' => 'int',
		'item_id_after' => 'int',
		'created_by' => 'int'
	];

	protected $fillable = [
		'item_id_before',
		'item_id_after',
		'created_by',
        'created_at'
	];

	public function createdBy()
	{
		return $this->belongsTo(\App\Models\Auth\User\User::class, 'created_by');
	}

	public function itemBefore()
	{
		return $this->belongsTo(\App\Models\Item::class, 'item_id_before');
	}

    public function itemAfter()
    {
        return $this->belongsTo(\App\Models\Item::class, 'item_id_after');
    }
}
