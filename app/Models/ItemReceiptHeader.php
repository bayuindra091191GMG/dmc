<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 19 Feb 2018 03:27:43 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ItemReceiptHeader
 * 
 * @property int $id
 * @property string $code
 * @property string $no_sj_spb
 * @property \Carbon\Carbon $date
 * @property int $delivery_note_id
 * @property string $remarks
 * @property int $status_id
 * @property int $created_by
 * @property \Carbon\Carbon $created_at
 * @property int $updated_by
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\DeliveryNoteHeader $delivery_note_header
 * @property \App\Models\Status $status
 * @property \App\Models\User $user
 * @property \Illuminate\Database\Eloquent\Collection $item_receipt_details
 *
 * @package App\Models
 */
class ItemReceiptHeader extends Eloquent
{
	protected $casts = [
		'delivery_note_id' => 'int',
		'status_id' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $dates = [
		'date'
	];

	protected $fillable = [
		'code',
		'no_sj_spb',
		'date',
		'delivery_note_id',
		'remarks',
		'status_id',
		'created_by',
		'updated_by'
	];

	public function delivery_note_header()
	{
		return $this->belongsTo(\App\Models\DeliveryNoteHeader::class, 'delivery_note_id');
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

	public function item_receipt_details()
	{
		return $this->hasMany(\App\Models\ItemReceiptDetail::class, 'header_id');
	}
}
