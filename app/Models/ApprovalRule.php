<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 25 Jan 2018 04:26:01 +0000.
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
		'updated_by',
        'created_at',
        'updated_at'
	];

	public function user()
	{
		return $this->belongsTo(\App\Models\Auth\User\User::class, 'user_id');
	}

	public function document()
	{
		return $this->belongsTo(\App\Models\Document::class);
	}

    public function createdBy()
    {
        return $this->belongsTo(\App\Models\Auth\User\User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(\App\Models\Auth\User\User::class, 'updated_by');
    }
}
