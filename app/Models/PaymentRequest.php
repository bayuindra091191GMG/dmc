<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 19 Jan 2018 04:10:48 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class PaymentRequest
 * 
 * @property int $id
 * @property string $code
 * @property \Carbon\Carbon $date
 * @property int $purchase_order_id
 * @property int $request_by
 * @property float $amount
 * @property float $ppn
 * @property float $pph_23
 * @property float $total_amount
 * @property string $requester_bank_name
 * @property string $requester_bank_account
 * @property string $requester_account_name
 * @property int $is_installment
 * @property string $note
 * @property int $status_id
 * @property int $created_by
 * @property \Carbon\Carbon $created_at
 * @property int $updated_by
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\Employee $employee
 * @property \App\Models\PurchaseOrderHeader $purchase_order_header
 * @property \App\Models\Auth\User\User $user
 * @property \Illuminate\Database\Eloquent\Collection $approval_payment_requests
 * @property \Illuminate\Database\Eloquent\Collection $payment_installments
 *
 * @package App\Models
 */
class PaymentRequest extends Eloquent
{
	protected $casts = [
		'purchase_order_id' => 'int',
		'request_by' => 'int',
		'amount' => 'float',
		'ppn' => 'float',
		'pph_23' => 'float',
		'total_amount' => 'float',
		'is_installment' => 'int',
		'status_id' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $dates = [
		'date'
	];

	protected $fillable = [
		'code',
		'date',
		'purchase_order_id',
		'request_by',
		'amount',
		'ppn',
		'pph_23',
		'total_amount',
		'requester_bank_name',
		'requester_bank_account',
		'requester_account_name',
		'is_installment',
		'note',
		'status_id',
		'created_by',
		'updated_by'
	];

	public function employee()
	{
		return $this->belongsTo(\App\Models\Employee::class, 'request_by');
	}

	public function purchase_order_header()
	{
		return $this->belongsTo(\App\Models\PurchaseOrderHeader::class, 'purchase_order_id');
	}

    public function createdBy()
    {
        return $this->belongsTo(\App\Models\Auth\User\User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(\App\Models\Auth\User\User::class, 'updated_by');
    }

	public function approval_payment_requests()
	{
		return $this->hasMany(\App\Models\ApprovalPaymentRequest::class);
	}

	public function payment_installments()
	{
		return $this->hasMany(\App\Models\PaymentInstallment::class);
	}
}
