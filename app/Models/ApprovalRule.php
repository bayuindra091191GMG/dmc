<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 25 Jan 2018 03:24:17 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ApprovalRule
 * 
 * @property int $id
 * @property int $document_id
 * @property int $user_id
 * @property \Carbon\Carbon $created_at
 * @property int $created_by
 * @property \Carbon\Carbon $updated_at
 * @property int $updated_by
 * 
 * @property \App\Models\User $user
 * @property \App\Models\Document $document
 *
 * @package App\Models
 */
class ApprovalRule extends Eloquent
{
	protected $casts = [
		'document_id' => 'int',
		'user_id' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $fillable = [
		'document_id',
		'user_id',
		'created_by',
		'updated_by'
	];

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class);
	}

	public function document()
	{
		return $this->belongsTo(\App\Models\Document::class);
	}
}
