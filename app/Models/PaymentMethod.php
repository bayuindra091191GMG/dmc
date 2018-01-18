<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 18 Jan 2018 07:30:31 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class PaymentMethod
 * 
 * @property int $id
 * @property string $description
 * @property float $fee
 * @property int $status_id
 * 
 * @property \App\Models\Status $status
 *
 * @package App\Models
 */
class PaymentMethod extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'fee' => 'float',
		'status_id' => 'int'
	];

	protected $fillable = [
		'description',
		'fee',
		'status_id'
	];

	public function status()
	{
		return $this->belongsTo(\App\Models\Status::class);
	}
}
