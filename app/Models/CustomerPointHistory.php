<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Nov 2019 13:30:52 +0700.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class CustomerPointHistory
 * 
 * @property int $id
 * @property int $customer_id
 * @property int $point_from
 * @property int $point_add
 * @property int $point_min
 * @property int $point_result
 * @property int $attendance_id
 * @property int $voucher_id
 * @property string $notes
 * @property \Carbon\Carbon $created_at
 * 
 * @property \App\Models\Customer $customer
 *
 * @package App\Models
 */
class CustomerPointHistory extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'customer_id' => 'int',
		'point_from' => 'int',
		'point_add' => 'int',
		'point_min' => 'int',
		'point_result' => 'int',
		'attendance_id' => 'int',
		'voucher_id' => 'int'
	];

	protected $fillable = [
		'customer_id',
		'point_from',
		'point_add',
		'point_min',
		'point_result',
		'attendance_id',
		'voucher_id',
		'notes'
	];

	public function customer()
	{
		return $this->belongsTo(\App\Models\Customer::class);
	}
}
