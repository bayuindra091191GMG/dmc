<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 22 May 2018 09:31:53 +0700.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class NumberingSystem
 * 
 * @property int $id
 * @property string $document
 * @property int $next_no
 *
 * @package App\Models
 */
class NumberingSystem extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'next_no' => 'int'
	];

	protected $fillable = [
		'document',
		'next_no'
	];
}
