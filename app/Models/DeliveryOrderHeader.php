<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 02 Mar 2018 16:09:30 +0700.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class DeliveryOrderHeader
 * 
 * @property int $id
 * @property string $code
 * @property int $purchase_request_id
 * @property int $from_site_id
 * @property int $to_site_id
 * @property int $machinery_id
 * @property string $remark
 * @property int $status_id
 * @property int $created_by
 * @property \Carbon\Carbon $created_at
 * @property int $updated_by
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\Machinery $machinery
 * @property \App\Models\PurchaseRequestHeader $purchase_request_header
 * @property \App\Models\Site $site
 * @property \App\Models\Status $status
 * @property \App\Models\Auth\User\User $user
 * @property \Illuminate\Database\Eloquent\Collection $delivery_order_details
 * @property \Illuminate\Database\Eloquent\Collection $item_receipt_headers
 *
 * @package App\Models
 */
class DeliveryOrderHeader extends Eloquent
{
	protected $casts = [
		'purchase_request_id' => 'int',
		'from_site_id' => 'int',
		'to_site_id' => 'int',
		'machinery_id' => 'int',
		'status_id' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $fillable = [
		'code',
		'purchase_request_id',
		'from_site_id',
		'to_site_id',
		'machinery_id',
		'remark',
		'status_id',
		'created_by',
		'updated_by'
	];

	public function machinery()
	{
		return $this->belongsTo(\App\Models\Machinery::class);
	}

	public function purchase_request_header()
	{
		return $this->belongsTo(\App\Models\PurchaseRequestHeader::class, 'purchase_request_id');
	}

	public function site()
	{
		return $this->belongsTo(\App\Models\Site::class, 'to_site_id');
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

	public function delivery_order_details()
	{
		return $this->hasMany(\App\Models\DeliveryOrderDetail::class, 'header_id');
	}

	public function item_receipt_headers()
	{
		return $this->hasMany(\App\Models\ItemReceiptHeader::class, 'delivery_order_id');
	}
}
