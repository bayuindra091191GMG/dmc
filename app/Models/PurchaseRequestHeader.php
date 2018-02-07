<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 07 Feb 2018 02:53:35 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class PurchaseRequestHeader
 * 
 * @property int $id
 * @property string $code
 * @property string $date
 * @property int $department_id
 * @property int $machinery_id
 * @property string $sn_chasis
 * @property string $sn_engine
 * @property int $status_id
 * @property int $created_by
 * @property \Carbon\Carbon $created_at
 * @property int $updated_by
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\Department $department
 * @property \App\Models\Machinery $machinery
 * @property \App\Models\Status $status
 * @property \App\Models\User $user
 * @property \Illuminate\Database\Eloquent\Collection $approval_purchase_requests
 * @property \Illuminate\Database\Eloquent\Collection $delivery_note_headers
 * @property \Illuminate\Database\Eloquent\Collection $issued_docket_headers
 * @property \Illuminate\Database\Eloquent\Collection $purchase_order_headers
 * @property \Illuminate\Database\Eloquent\Collection $purchase_request_details
 * @property \Illuminate\Database\Eloquent\Collection $quotation_headers
 *
 * @package App\Models
 */
class PurchaseRequestHeader extends Eloquent
{
	protected $casts = [
		'department_id' => 'int',
		'machinery_id' => 'int',
		'status_id' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $fillable = [
		'code',
		'date',
		'department_id',
		'machinery_id',
		'sn_chasis',
		'sn_engine',
		'status_id',
		'created_by',
		'updated_by'
	];

	public function department()
	{
		return $this->belongsTo(\App\Models\Department::class);
	}

	public function machinery()
	{
		return $this->belongsTo(\App\Models\Machinery::class);
	}

	public function status()
	{
		return $this->belongsTo(\App\Models\Status::class);
	}

    public function createdBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
    }

	public function approval_purchase_requests()
	{
		return $this->hasMany(\App\Models\ApprovalPurchaseRequest::class, 'purchase_request_id');
	}

	public function delivery_note_headers()
	{
		return $this->hasMany(\App\Models\DeliveryNoteHeader::class, 'purchase_request_id');
	}

	public function issued_docket_headers()
	{
		return $this->hasMany(\App\Models\IssuedDocketHeader::class, 'purchase_request_id');
	}

	public function purchase_order_headers()
	{
		return $this->hasMany(\App\Models\PurchaseOrderHeader::class, 'purchasing_request_id');
	}

	public function purchase_request_details()
	{
		return $this->hasMany(\App\Models\PurchaseRequestDetail::class, 'header_id');
	}

	public function quotation_headers()
	{
		return $this->hasMany(\App\Models\QuotationHeader::class, 'purchase_request_id');
	}
}
