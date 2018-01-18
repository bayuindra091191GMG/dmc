<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 18 Jan 2018 07:30:31 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Document
 * 
 * @property int $id
 * @property string $description
 *
 * @package App\Models
 */
class Document extends Eloquent
{
	public $timestamps = false;

	protected $fillable = [
		'description'
	];
}
