<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 01 Mar 2018 10:23:31 +0700.
 */

namespace App\Models;

use Carbon\Carbon;
use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class PurchaseRequestHeader
 * 
 * @property int $id
 * @property string $code
 * @property int $department_id
 * @property int $machinery_id
 * @property string $priority
 * @property string $km
 * @property string $hm
 * @property int $status_id
 * @property \Carbon\Carbon $date
 * @property int $created_by
 * @property \Carbon\Carbon $created_at
 * @property int $updated_by
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\Department $department
 * @property \App\Models\Machinery $machinery
 * @property \App\Models\Status $status
 * @property \App\Models\Auth\User\User $user
 * @property \Illuminate\Database\Eloquent\Collection $approval_purchase_requests
 * @property \Illuminate\Database\Eloquent\Collection $delivery_order_headers
 * @property \Illuminate\Database\Eloquent\Collection $issued_docket_headers
 * @property \Illuminate\Database\Eloquent\Collection $purchase_order_headers
 * @property \Illuminate\Database\Eloquent\Collection $purchase_request_details
 * @property \Illuminate\Database\Eloquent\Collection $quotation_headers
 *
 * @package App\Models
 */
class PurchaseRequestHeader extends Eloquent
{
    protected $appends = ['date_string'];

	protected $casts = [
		'department_id' => 'int',
		'machinery_id' => 'int',
		'status_id' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $fillable = [
		'code',
		'department_id',
		'machinery_id',
		'priority',
		'km',
		'hm',
		'status_id',
        'date',
		'created_by',
		'updated_by'
	];

    public function getDateStringAttribute(){
        return Carbon::parse($this->attributes['created_at'])->format('d M Y');
    }

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
        return $this->belongsTo(\App\Models\Auth\User\User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(\App\Models\Auth\User\User::class, 'updated_by');
    }

	public function approval_purchase_requests()
	{
		return $this->hasMany(\App\Models\ApprovalPurchaseRequest::class, 'purchase_request_id');
	}

	public function delivery_order_headers()
	{
		return $this->hasMany(\App\Models\DeliveryOrderHeader::class, 'purchase_request_id');
	}

	public function issued_docket_headers()
	{
		return $this->hasMany(\App\Models\IssuedDocketHeader::class, 'purchase_request_id');
	}

	public function purchase_order_headers()
	{
		return $this->hasMany(\App\Models\PurchaseOrderHeader::class, 'purchase_request_id');
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
