<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Nov 2019 13:44:27 +0700.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class CustomerVoucher
 * 
 * @property int $id
 * @property int $customer_id
 * @property int $voucher_id
 * @property int $quantity
 * @property \Carbon\Carbon $created_at
 * @property int $status_id
 * 
 * @property \App\Models\Customer $customer
 * @property \App\Models\Status $status
 * @property \App\Models\Voucher $voucher
 *
 * @package App\Models
 */
class CustomerVoucher extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'customer_id' => 'int',
		'voucher_id' => 'int',
		'quantity' => 'int',
		'status_id' => 'int'
	];

	protected $fillable = [
		'customer_id',
		'voucher_id',
		'quantity',
		'status_id'
	];

	public function customer()
	{
		return $this->belongsTo(\App\Models\Customer::class);
	}

	public function status()
	{
		return $this->belongsTo(\App\Models\Status::class);
	}

	public function voucher()
	{
		return $this->belongsTo(\App\Models\Voucher::class);
	}
}
