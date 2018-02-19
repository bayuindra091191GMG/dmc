<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 19 Feb 2018 04:33:20 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Document
 * 
 * @property int $id
 * @property string $description
 * @property string $code
 * 
 * @property \Illuminate\Database\Eloquent\Collection $approval_rules
 * @property \Illuminate\Database\Eloquent\Collection $numbering_systems
 * @property \Illuminate\Database\Eloquent\Collection $permission_documents
 *
 * @package App\Models
 */
class Document extends Eloquent
{
	public $timestamps = false;

	protected $fillable = [
		'description',
		'code'
	];

	public function approval_rules()
	{
		return $this->hasMany(\App\Models\ApprovalRule::class);
	}

	public function numbering_systems()
	{
		return $this->hasMany(\App\Models\NumberingSystem::class, 'doc_id');
	}

	public function permission_documents()
	{
		return $this->hasMany(\App\Models\PermissionDocument::class);
	}
}
