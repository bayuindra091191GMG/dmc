<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 18 Jan 2018 07:30:31 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ProtectionShopToken
 * 
 * @property int $id
 * @property int $user_id
 * @property string $number
 * @property \Carbon\Carbon $expires
 * @property string $success_url
 * @property string $cancel_url
 * @property string $success_url_title
 * @property string $cancel_url_title
 * @property string $shop_url
 *
 * @package App\Models
 */
class ProtectionShopToken extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int'
	];

	protected $dates = [
		'expires'
	];

	protected $fillable = [
		'user_id',
		'number',
		'expires',
		'success_url',
		'cancel_url',
		'success_url_title',
		'cancel_url_title',
		'shop_url'
	];
}
