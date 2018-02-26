<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 26 Feb 2018 11:05:00 +0700.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Supplier
 * 
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property \Carbon\Carbon $contract_start_date
 * @property \Carbon\Carbon $contract_finish_date
 * @property int $created_by
 * @property \Carbon\Carbon $created_at
 * @property int $updated_by
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\Auth\User\User $user
 * @property \Illuminate\Database\Eloquent\Collection $purchase_order_headers
 * @property \Illuminate\Database\Eloquent\Collection $quotation_headers
 *
 * @package App\Models
 */
class Supplier extends Eloquent
{
	protected $casts = [
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $dates = [
		'contract_start_date',
		'contract_finish_date'
	];

	protected $fillable = [
		'code',
		'name',
		'email',
		'phone',
		'contract_start_date',
		'contract_finish_date',
		'created_by',
		'updated_by'
	];

    public function createdBy()
    {
        return $this->belongsTo(\App\Models\Auth\User\User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(\App\Models\Auth\User\User::class, 'updated_by');
    }

	public function purchase_order_headers()
	{
		return $this->hasMany(\App\Models\PurchaseOrderHeader::class);
	}

	public function quotation_headers()
	{
		return $this->hasMany(\App\Models\QuotationHeader::class);
	}
}
