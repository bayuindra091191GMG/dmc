<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 24 Jan 2018 04:48:30 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class PermissionDocument
 * 
 * @property int $id
 * @property int $role_id
 * @property int $document_id
 * @property int $create
 * @property int $update
 * @property int $delete
 * @property int $read
 * @property int $print
 * @property \Carbon\Carbon $created_at
 * @property int $created_by
 * @property \Carbon\Carbon $updated_at
 * @property int $updated_by
 * 
 * @property \App\Models\Document $document
 * @property \App\Models\Auth\Role\Role $role
 * @property \App\Models\Auth\User\User $user
 *
 * @package App\Models
 */
class PermissionDocument extends Eloquent
{
	protected $casts = [
		'role_id' => 'int',
		'document_id' => 'int',
		'create' => 'int',
		'update' => 'int',
		'delete' => 'int',
		'read' => 'int',
		'print' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $fillable = [
		'role_id',
		'document_id',
		'create',
		'update',
		'delete',
		'read',
		'print',
		'created_by',
		'updated_by'
	];

	public function document()
	{
		return $this->belongsTo(\App\Models\Document::class);
	}

	public function role()
	{
		return $this->belongsTo(\App\Models\Auth\Role\Role::class);
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
