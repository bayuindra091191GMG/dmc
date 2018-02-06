<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 06 Feb 2018 06:32:47 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class IssuedDocketDetail
 * 
 * @property int $id
 * @property int $header_id
 * @property int $item_id
 * @property int $machinery_id
 * @property string $time
 * @property int $quantity
 * @property string $remarks
 * 
 * @property \App\Models\IssuedDocketHeader $issued_docket_header
 * @property \App\Models\Item $item
 * @property \App\Models\Machinery $machinery
 *
 * @package App\Models
 */
class IssuedDocketDetail extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'header_id' => 'int',
		'item_id' => 'int',
		'machinery_id' => 'int',
		'quantity' => 'int'
	];

	protected $fillable = [
		'header_id',
		'item_id',
		'machinery_id',
		'time',
		'quantity',
		'remarks'
	];

	public function issued_docket_header()
	{
		return $this->belongsTo(\App\Models\IssuedDocketHeader::class, 'header_id');
	}

	public function item()
	{
		return $this->belongsTo(\App\Models\Item::class);
	}

	public function machinery()
	{
		return $this->belongsTo(\App\Models\Machinery::class);
	}
}
