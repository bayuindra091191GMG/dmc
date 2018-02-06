<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 06 Feb 2018 06:32:11 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class DeliveryNoteHeader
 * 
 * @property int $id
 * @property string $code
 * @property int $purchase_request_id
 * @property int $departure_site_id
 * @property int $arrival_site_id
 * @property int $status_id
 * @property int $created_by
 * @property \Carbon\Carbon $created_at
 * @property int $updated_by
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\PurchaseRequestHeader $purchase_request_header
 * @property \App\Models\Site $site
 * @property \App\Models\Status $status
 * @property \App\Models\User $user
 * @property \Illuminate\Database\Eloquent\Collection $delivery_note_details
 * @property \Illuminate\Database\Eloquent\Collection $item_receipt_headers
 *
 * @package App\Models
 */
class DeliveryNoteHeader extends Eloquent
{
	protected $casts = [
		'purchase_request_id' => 'int',
		'departure_site_id' => 'int',
		'arrival_site_id' => 'int',
		'status_id' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $fillable = [
		'code',
		'purchase_request_id',
		'departure_site_id',
		'arrival_site_id',
		'status_id',
		'created_by',
		'updated_by'
	];

	public function purchase_request_header()
	{
		return $this->belongsTo(\App\Models\PurchaseRequestHeader::class, 'purchase_request_id');
	}

	public function site()
	{
		return $this->belongsTo(\App\Models\Site::class, 'departure_site_id');
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

	public function delivery_note_details()
	{
		return $this->hasMany(\App\Models\DeliveryNoteDetail::class, 'header_id');
	}

	public function item_receipt_headers()
	{
		return $this->hasMany(\App\Models\ItemReceiptHeader::class, 'delivery_note_id');
	}
}
