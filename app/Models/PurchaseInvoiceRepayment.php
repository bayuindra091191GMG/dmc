<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 06 May 2018 13:33:42 +0700.
 */

namespace App\Models;

use Carbon\Carbon;
use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class PurchaseInvoiceRepayment
 * 
 * @property int $id
 * @property int $purchase_invoice_header_id
 * @property float $repayment_amount
 * @property \Carbon\Carbon $date
 * @property int $created_by
 * 
 * @property \App\Models\PurchaseInvoiceHeader $purchase_invoice_header
 * @property \App\Models\Auth\User\User $user
 *
 * @package App\Models
 */
class PurchaseInvoiceRepayment extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'purchase_invoice_header_id' => 'int',
		'repayment_amount' => 'float',
		'created_by' => 'int'
	];

    protected $appends = [
        'date_string',
        'repayment_amount_string',
    ];

	protected $dates = [
		'date'
	];

	protected $fillable = [
		'purchase_invoice_header_id',
		'repayment_amount',
		'date',
		'created_by'
	];

    public function getDateStringAttribute(){
        return Carbon::parse($this->attributes['date'])->format('d M Y');
    }

    public function getRepaymentAmountStringAttribute(){
        return number_format($this->attributes['repayment_amount'], 0, ",", ".");
    }

	public function purchase_invoice_header()
	{
		return $this->belongsTo(\App\Models\PurchaseInvoiceHeader::class);
	}

	public function user()
	{
		return $this->belongsTo(\App\Models\Auth\User\User::class, 'created_by');
	}
}
