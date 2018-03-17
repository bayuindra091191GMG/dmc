<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 21 Feb 2018 07:11:16 +0000.
 */

namespace App\Models;

use Carbon\Carbon;
use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ItemReceiptHeader
 * 
 * @property int $id
 * @property string $code
 * @property string $no_sj_spb
 * @property \Carbon\Carbon $date
 * @property int $delivery_order_id
 * @property string $delivered_from
 * @property string $angkutan
 * @property int $status_id
 * @property int $created_by
 * @property \Carbon\Carbon $created_at
 * @property int $updated_by
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\DeliveryOrderHeader $delivery_order_header
 * @property \App\Models\Status $status
 * @property \App\Models\User $user
 * @property \Illuminate\Database\Eloquent\Collection $item_receipt_details
 *
 * @package App\Models
 */
class ItemReceiptHeader extends Eloquent
{
    protected $appends = ['date_string'];

	protected $casts = [
		'purchase_order_id' => 'int',
        'warehouse_id'  => 'int',
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
		'delivery_order_vendor',
        'purchase_order_id',
        'warehouse_id',
		'status_id',
		'created_by',
		'updated_by'
	];

    public function getDateStringAttribute(){
        return Carbon::parse($this->attributes['date'])->format('d M Y');
    }

    public function purchase_order_header()
    {
        return $this->belongsTo(\App\Models\PurchaseOrderHeader::class, 'purchase_order_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(\App\Models\Warehouse::class, 'warehouse_id');
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
