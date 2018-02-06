<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 06 Feb 2018 06:32:24 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class DeliveryNoteDetail
 * 
 * @property int $id
 * @property int $header_id
 * @property int $item_id
 * @property string $remarks
 * 
 * @property \App\Models\DeliveryNoteHeader $delivery_note_header
 * @property \App\Models\Item $item
 *
 * @package App\Models
 */
class DeliveryNoteDetail extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'header_id' => 'int',
		'item_id' => 'int'
	];

	protected $fillable = [
		'header_id',
		'item_id',
		'remarks'
	];

	public function delivery_note_header()
	{
		return $this->belongsTo(\App\Models\DeliveryNoteHeader::class, 'header_id');
	}

	public function item()
	{
		return $this->belongsTo(\App\Models\Item::class);
	}
}
