<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 22 Mar 2018 14:38:06 +0700.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class MaterialRequestDetail
 * 
 * @property int $id
 * @property int $header_id
 * @property int $item_id
 * @property int $quantity
 * @property int $quantity_received
 * @property int $quantity_issued
 * @property string $remark
 * 
 * @property \App\Models\Item $item
 * @property \App\Models\MaterialRequestHeader $material_request_header
 *
 * @package App\Models
 */
class MaterialRequestDetail extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'header_id' => 'int',
		'item_id' => 'int',
		'quantity' => 'int',
		'quantity_received' => 'int',
		'quantity_issued' => 'int'
	];

	protected $fillable = [
		'header_id',
		'item_id',
		'quantity',
		'quantity_received',
		'quantity_issued',
		'remark'
	];

	public function item()
	{
		return $this->belongsTo(\App\Models\Item::class);
	}

	public function material_request_header()
	{
		return $this->belongsTo(\App\Models\MaterialRequestHeader::class, 'header_id');
	}
}
