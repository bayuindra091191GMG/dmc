<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 06 Mar 2018 14:19:33 +0700.
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
 * @property string $fax
 * @property string $cellphone
 * @property string $contact_person
 * @property string $address
 * @property string $city
 * @property string $remark
 * @property string $npwp
 * @property string $bank_name
 * @property string $bank_account_number
 * @property string $bank_account_name
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

	protected $fillable = [
		'code',
		'name',
		'email',
		'phone',
		'fax',
		'cellphone',
		'contact_person',
		'address',
		'city',
		'remark',
		'npwp',
		'bank_name',
		'bank_account_number',
		'bank_account_name',
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
