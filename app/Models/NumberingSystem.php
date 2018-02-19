<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 19 Feb 2018 04:33:09 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class NumberingSystem
 * 
 * @property int $id
 * @property int $doc_id
 * @property int $next_no
 * 
 * @property \App\Models\Document $document
 *
 * @package App\Models
 */
class NumberingSystem extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'doc_id' => 'int',
		'next_no' => 'int'
	];

	protected $fillable = [
		'doc_id',
		'next_no'
	];

	public function document()
	{
		return $this->belongsTo(\App\Models\Document::class, 'doc_id');
	}
}
