<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 02 Oct 2019 10:46:19 +0700.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Autonumber
 * 
 * @property string $id
 * @property int $next_no
 *
 * @package App\Models
 */
class Autonumber extends Eloquent
{
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'next_no' => 'int'
	];

	protected $fillable = [
		'next_no'
	];
}
