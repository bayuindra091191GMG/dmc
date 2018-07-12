<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 23 May 2018 13:55:57 +0700.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class TransactionDetail
 * 
 * @property int $id
 * @property int $header_id
 * @property int $schedule_id
 * @property int $prorate
 * @property string $day
 * @property int $meeting_attendeds
 * @property float $price
 * @property float $prorate_price
 * @property float $discount
 * @property float $subtotal
 * @property \Carbon\Carbon $updated_at
 * @property int $updated_by
 * 
 * @property \App\Models\TransactionHeader $transaction_header
 * @property \App\Models\Schedule $schedule
 * @property \App\Models\Auth\User\User $user
 *
 * @package App\Models
 */
class TransactionDetail extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'header_id' => 'int',
		'schedule_id' => 'int',
		'meeting_attendeds' => 'int',
		'price' => 'float',
		'discount' => 'float',
		'subtotal' => 'float',
		'updated_by' => 'int'
	];

	protected $dates = [
		'class_start_date',
		'class_end_date'
	];

	protected $fillable = [
		'header_id',
		'schedule_id',
        'prorate',
		'day',
		'meeting_attendeds',
		'price',
        'prorate_price',
		'discount',
		'subtotal',
		'updated_by'
	];

    protected $appends = [
        'price_string',
        'prorate_price_string',
        'discount_string',
        'subtotal_string'
    ];

    public function getPriceStringAttribute(){
        return number_format($this->attributes['price'], 0, ",", ".");
    }

    public function getProratePriceStringAttribute(){
        if(!empty($this->attributes['prorate_price'])){
            return number_format($this->attributes['prorate_price'], 0, ",", ".");
        }
        else{
            return null;
        }

    }

    public function getDiscountStringAttribute(){
        if(!empty($this->attributes['discount'])){
            return number_format($this->attributes['discount'], 0, ",", ".");
        }
        else{
            return null;
        }
    }

    public function getSubtotalStringAttribute(){
        return number_format($this->attributes['subtotal'], 0, ",", ".");
    }

	public function transaction_header()
	{
		return $this->belongsTo(\App\Models\TransactionHeader::class, 'header_id');
	}

	public function schedule()
	{
		return $this->belongsTo(\App\Models\Schedule::class, 'schedule_id');
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
