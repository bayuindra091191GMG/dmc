<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 15 May 2018 15:30:52 +0700.
 */

namespace App\Models;

use Carbon\Carbon;
use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class TransactionHeader
 * 
 * @property int $id
 * @property string $code
 * @property int $type
 * @property int $customer_id
 * @property \Carbon\Carbon $date
 * @property string $payment_method
 * @property float $total_price
 * @property float $total_prorate_price
 * @property float $total_discount
 * @property float $registration_fee
 * @property float $total_payment
 * @property string $invoice_number
 * @property int $status_id
 * @property \Carbon\Carbon $created_at
 * @property int $created_by
 * @property \Carbon\Carbon $updated_at
 * @property int $updated_by
 * 
 * @property \App\Models\Customer $customer
 * @property \App\Models\Status $status
 * @property \App\Models\Auth\User\User $user
 * @property \Illuminate\Database\Eloquent\Collection $transaction_details
 *
 * @package App\Models
 */
class TransactionHeader extends Eloquent
{
	protected $casts = [
	    'type' => 'int',
		'customer_id' => 'int',
		'total_price' => 'float',
        'total_prorate_price' => 'float',
		'total_discount' => 'float',
		'total_payment' => 'float',
        'registration_fee' => 'float',
		'status_id' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $dates = [
		'date'
	];

	protected $fillable = [
	    'code',
        'type',
		'customer_id',
		'date',
        'payment_method',
		'total_price',
        'total_prorate_price',
		'total_discount',
		'total_payment',
        'registration_fee',
		'invoice_number',
		'status_id',
		'created_by',
		'updated_by'
	];

    protected $appends = [
        'total_price_string',
        'total_prorate_price_string',
        'total_discount_string',
        'total_payment_string',
        'registration_fee_string',
        'date_string',
    ];

    public function getDateStringAttribute(){
        return Carbon::parse($this->attributes['date'])->format('d M Y');
    }

    public function getRegistrationFeeStringAttribute(){
        return number_format($this->attributes['registration_fee'], 0, ",", ".");
    }

    public function getTotalPriceStringAttribute(){
        return number_format($this->attributes['total_price'], 0, ",", ".");
    }

    public function getTotalProratePriceStringAttribute(){
        return number_format($this->attributes['total_prorate_price'], 0, ",", ".");
    }

    public function getTotalDiscountStringAttribute(){
        return number_format($this->attributes['total_discount'], 0, ",", ".");
    }

    public function getTotalPaymentStringAttribute(){
        return number_format($this->attributes['total_payment'], 0, ",", ".");
    }

	public function customer()
	{
		return $this->belongsTo(\App\Models\Customer::class, 'customer_id');
	}

	public function status()
	{
		return $this->belongsTo(\App\Models\Status::class);
	}

    public function createdBy()
    {
        return $this->belongsTo(\App\Models\Auth\User\User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(\App\Models\Auth\User\User::class, 'updated_by');
    }

	public function transaction_details()
	{
		return $this->hasMany(\App\Models\TransactionDetail::class, 'header_id');
	}

    public function leaves()
    {
        return $this->hasMany(\App\Models\Leaf::class, 'transaction_id');
    }
}
