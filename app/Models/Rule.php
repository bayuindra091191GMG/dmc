<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 19 Jan 2018 06:48:33 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Rule
 * 
 * @property int $id
 * @property int $document_id
 * @property string $description
 * @property int $total_approval_users
 * 
 * @property \Illuminate\Database\Eloquent\Collection $approval_rules
 *
 * @package App\Models
 */
class Rule extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'document_id' => 'int',
		'total_approval_users' => 'int'
	];

	protected $fillable = [
		'document_id',
		'description',
		'total_approval_users'
	];

	public function approval_rules()
	{
		return $this->hasMany(\App\Models\ApprovalRule::class);
	}
}
