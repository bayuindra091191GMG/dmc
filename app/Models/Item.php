<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 02 May 2018 10:52:46 +0700.
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
 * @property int $stock_minimum
 * @property int $stock_notification
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
 * @property \Illuminate\Database\Eloquent\Collection $delivery_order_details
 * @property \Illuminate\Database\Eloquent\Collection $interchanges
 * @property \Illuminate\Database\Eloquent\Collection $issued_docket_details
 * @property \Illuminate\Database\Eloquent\Collection $item_mutations
 * @property \Illuminate\Database\Eloquent\Collection $item_receipt_details
 * @property \Illuminate\Database\Eloquent\Collection $item_stock_notifications
 * @property \Illuminate\Database\Eloquent\Collection $item_stocks
 * @property \Illuminate\Database\Eloquent\Collection $material_request_details
 * @property \Illuminate\Database\Eloquent\Collection $purchase_invoice_details
 * @property \Illuminate\Database\Eloquent\Collection $purchase_order_details
 * @property \Illuminate\Database\Eloquent\Collection $purchase_request_details
 * @property \Illuminate\Database\Eloquent\Collection $quotation_details
 * @property \Illuminate\Database\Eloquent\Collection $serials
 * @property \Illuminate\Database\Eloquent\Collection $stock_adjustments
 * @property \Illuminate\Database\Eloquent\Collection $stock_cards
 * @property \Illuminate\Database\Eloquent\Collection $stock_ins
 *
 * @package App\Models
 */
class Item extends Eloquent
{
	protected $casts = [
		'stock' => 'int',
		'stock_minimum' => 'int',
		'stock_notification' => 'int',
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
		'stock_minimum',
		'stock_notification',
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

	public function delivery_order_details()
	{
		return $this->hasMany(\App\Models\DeliveryOrderDetail::class);
	}

	public function interchanges()
	{
		return $this->hasMany(\App\Models\Interchange::class, 'item_id_before');
	}

	public function issued_docket_details()
	{
		return $this->hasMany(\App\Models\IssuedDocketDetail::class);
	}

	public function item_mutations()
	{
		return $this->hasMany(\App\Models\ItemMutation::class);
	}

	public function item_receipt_details()
	{
		return $this->hasMany(\App\Models\ItemReceiptDetail::class);
	}

	public function item_stock_notifications()
	{
		return $this->hasMany(\App\Models\ItemStockNotification::class);
	}

	public function item_stocks()
	{
		return $this->hasMany(\App\Models\ItemStock::class);
	}

	public function material_request_details()
	{
		return $this->hasMany(\App\Models\MaterialRequestDetail::class);
	}

	public function purchase_invoice_details()
	{
		return $this->hasMany(\App\Models\PurchaseInvoiceDetail::class);
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

	public function stock_cards()
	{
		return $this->hasMany(\App\Models\StockCard::class);
	}

	public function stock_ins()
	{
		return $this->hasMany(\App\Models\StockIn::class);
	}
}
