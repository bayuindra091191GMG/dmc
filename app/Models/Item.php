<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 21 Feb 2018 10:00:46 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Item
 * 
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $part_number
 * @property int $stock
 * @property float $value
 * @property int $is_serial
 * @property string $uom
 * @property int $group_id
 * @property int $warehouse_id
 * @property string $machinery_type
 * @property string $description
 * @property int $created_by
 * @property \Carbon\Carbon $created_at
 * @property int $updated_by
 * @property \Carbon\Carbon $updated_at
 *
 * @property \App\Models\Auth\User\User $user
 * @property \App\Models\Group $group
 * @property \App\Models\Warehouse $warehouse
 * @property \Illuminate\Database\Eloquent\Collection $delivery_note_details
 * @property \Illuminate\Database\Eloquent\Collection $issued_docket_details
 * @property \Illuminate\Database\Eloquent\Collection $item_receipt_details
 * @property \Illuminate\Database\Eloquent\Collection $purchase_order_details
 * @property \Illuminate\Database\Eloquent\Collection $purchase_request_details
 * @property \Illuminate\Database\Eloquent\Collection $quotation_details
 * @property \Illuminate\Database\Eloquent\Collection $serials
 * @property \Illuminate\Database\Eloquent\Collection $stock_adjustments
 * @property \Illuminate\Database\Eloquent\Collection $stock_ins
 * @property \Illuminate\Database\Eloquent\Collection $item_stocks
 *
 * @package App\Models
 */
class Item extends Eloquent
{
	protected $casts = [
		'stock' => 'int',
		'value' => 'float',
		'is_serial' => 'int',
		'group_id' => 'int',
		'warehouse_id' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $fillable = [
		'code',
		'name',
        'part_number',
		'stock',
		'value',
		'is_serial',
		'uom',
		'group_id',
		'warehouse_id',
		'machinery_type',
		'description',
		'created_by',
		'updated_by'
	];


    public function createdBy()
    {
        return $this->belongsTo(\App\Models\Auth\User\User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(\App\Models\Auth\User\User::class, 'updated_by');
    }

	public function group()
	{
		return $this->belongsTo(\App\Models\Group::class);
	}

	public function warehouse()
	{
		return $this->belongsTo(\App\Models\Warehouse::class);
	}


	public function delivery_note_details()
	{
		return $this->hasMany(\App\Models\DeliveryNoteDetail::class);
	}

	public function issued_docket_details()
	{
		return $this->hasMany(\App\Models\IssuedDocketDetail::class);
	}

	public function item_receipt_details()
	{
		return $this->hasMany(\App\Models\ItemReceiptDetail::class);
	}

	public function purchase_order_details()
	{
		return $this->hasMany(\App\Models\PurchaseOrderDetail::class);
	}

	public function purchase_request_details()
	{
		return $this->hasMany(\App\Models\PurchaseRequestDetail::class);
	}

	public function quotation_details()
	{
		return $this->hasMany(\App\Models\QuotationDetail::class);
	}

	public function serials()
	{
		return $this->hasMany(\App\Models\Serial::class);
	}

	public function stock_adjustments()
	{
		return $this->hasMany(\App\Models\StockAdjustment::class);
	}

	public function stock_ins()
	{
		return $this->hasMany(\App\Models\StockIn::class);
	}

	public function item_stocks()
	{
		return $this->hasMany(\App\Models\ItemStock::class);
	}
}
