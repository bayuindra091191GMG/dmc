<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 25 Jan 2018 03:24:39 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ApprovalRoleRule
 * 
 * @property int $id
 * @property int $document_id
 * @property string $description
 * @property int $total_approval_users
 *
 * @package App\Models
 */
class ApprovalRoleRule extends Eloquent
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
}
