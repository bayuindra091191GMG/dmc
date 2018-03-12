<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 12 Mar 2018 14:58:54 +0700.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class PreferenceCompany
 * 
 * @property int $id
 * @property string $address
 * @property string $phone
 * @property string $fax
 * @property string $email
 * @property \Carbon\Carbon $created_at
 * @property int $created_by
 * @property \Carbon\Carbon $updated_at
 * @property int $updated_by
 *
 * @package App\Models
 */
class PreferenceCompany extends Eloquent
{
	protected $casts = [
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $fillable = [
		'address',
		'phone',
		'fax',
		'email',
		'created_by',
		'updated_by'
	];
}
