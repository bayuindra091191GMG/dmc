<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 19 Mar 2018 14:34:32 +0700.
 */

namespace App\Models;

use Carbon\Carbon;
use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class PaymentRequest
 * 
 * @property int $id
 * @property string $code
 * @property \Carbon\Carbon $date
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
 * @property \App\Models\User $user
 * @property \Illuminate\Database\Eloquent\Collection $approval_payment_requests
 * @property \Illuminate\Database\Eloquent\Collection $payment_installments
 * @property \Illuminate\Database\Eloquent\Collection $payment_requests_pi_details
 * @property \Illuminate\Database\Eloquent\Collection $payment_requests_po_details
 *
 * @package App\Models
 */
class PaymentRequest extends Eloquent
{
    protected $appends = ['date_string'];

	protected $casts = [
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

    public function getDateStringAttribute(){
        return Carbon::parse($this->attributes['created_at'])->format('d M Y');
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

	public function payment_requests_pi_details()
	{
		return $this->hasMany(\App\Models\PaymentRequestsPiDetail::class, 'payment_requests_id');
	}

	public function payment_requests_po_details()
	{
		return $this->hasMany(\App\Models\PaymentRequestsPoDetail::class, 'payment_requests_id');
	}
}
