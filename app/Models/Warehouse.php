<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 19 Mar 2018 14:15:13 +0700.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Warehouse
 * 
 * @property int $id
 * @property string $code
 * @property int $site_id
 * @property string $name
 * @property string $phone
 * 
 * @property \App\Models\Site $site
 * @property \Illuminate\Database\Eloquent\Collection $delivery_order_headers
 * @property \Illuminate\Database\Eloquent\Collection $item_mutations
 * @property \Illuminate\Database\Eloquent\Collection $item_receipt_headers
 * @property \Illuminate\Database\Eloquent\Collection $item_stocks
 * @property \Illuminate\Database\Eloquent\Collection $items
 * @property \Illuminate\Database\Eloquent\Collection $serials
 * @property \Illuminate\Database\Eloquent\Collection $stock_ins
 *
 * @package App\Models
 */
class Warehouse extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'site_id' => 'int'
	];

	protected $fillable = [
		'code',
		'site_id',
		'name',
		'phone'
	];

	public function site()
	{
		return $this->belongsTo(\App\Models\Site::class);
	}

	public function delivery_order_headers_toWarehouse()
	{
		return $this->hasMany(\App\Models\DeliveryOrderHeader::class, 'to_warehouse_id');
	}

    public function delivery_order_headers_fromWarehouse()
    {
        return $this->hasMany(\App\Models\DeliveryOrderHeader::class, 'from_warehouse_id');
    }

	public function item_mutations()
	{
		return $this->hasMany(\App\Models\ItemMutation::class, 'to_warehouse_id');
	}

	public function item_receipt_headers()
	{
		return $this->hasMany(\App\Models\ItemReceiptHeader::class);
	}

	public function item_stocks()
	{
		return $this->hasMany(\App\Models\ItemStock::class);
	}

	public function items()
	{
		return $this->hasMany(\App\Models\Item::class);
	}

	public function serials()
	{
		return $this->hasMany(\App\Models\Serial::class);
	}

	public function stock_ins()
	{
		return $this->hasMany(\App\Models\StockIn::class);
	}
}
