<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 18 Jan 2018 07:30:31 +0000.
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
 * @property \Carbon\Carbon $created_at
 * @property int $created_by
 * @property \Carbon\Carbon $updated_at
 * @property int $updated_by
 *
 * @package App\Models
 */
class Rule extends Eloquent
{
	protected $casts = [
		'document_id' => 'int',
		'total_approval_users' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $fillable = [
		'document_id',
		'description',
		'total_approval_users',
		'created_by',
		'updated_by'
	];
}
