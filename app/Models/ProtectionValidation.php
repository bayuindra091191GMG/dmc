<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 18 Jan 2018 07:30:31 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ProtectionValidation
 * 
 * @property int $id
 * @property int $user_id
 * @property \Carbon\Carbon $ttl
 * @property string $validation_result
 *
 * @package App\Models
 */
class ProtectionValidation extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int'
	];

	protected $dates = [
		'ttl'
	];

	protected $fillable = [
		'user_id',
		'ttl',
		'validation_result'
	];
}
