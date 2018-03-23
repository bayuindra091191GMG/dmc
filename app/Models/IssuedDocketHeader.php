<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 15 Feb 2018 04:00:49 +0000.
 */

namespace App\Models;

use Carbon\Carbon;
use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class IssuedDocketHeader
 * 
 * @property int $id
 * @property string $code
 * @property \Carbon\Carbon $date
 * @property int $unit_id
 * @property int $purchase_request_id
 * @property int $department_id
 * @property string $division
 * @property int $status_id
 * @property int $created_by
 * @property \Carbon\Carbon $created_at
 * @property int $updated_by
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\Department $department
 * @property \App\Models\Machinery $machinery
 * @property \App\Models\PurchaseRequestHeader $purchase_request_header
 * @property \App\Models\Status $status
 * @property \App\Models\User $user
 * @property \Illuminate\Database\Eloquent\Collection $issued_docket_details
 *
 * @package App\Models
 */
class IssuedDocketHeader extends Eloquent
{
    protected $appends = ['date_string'];

	protected $casts = [
		'unit_id' => 'int',
		'purchase_request_id' => 'int',
		'department_id' => 'int',
		'status_id' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $dates = [
		'date'
	];

	protected $fillable = [
		'code',
		'date',
        'km',
        'hm',
		'unit_id',
		'purchase_request_id',
		'department_id',
		'division',
		'status_id',
		'created_by',
		'updated_by'
	];

	public function getDateStringAttribute(){
	    return Carbon::parse($this->attributes['date'])->format('d M Y');
    }

	public function department()
	{
		return $this->belongsTo(\App\Models\Department::class);
	}

	public function machinery()
	{
		return $this->belongsTo(\App\Models\Machinery::class, 'unit_id');
	}

	public function purchase_request_header()
	{
		return $this->belongsTo(\App\Models\PurchaseRequestHeader::class, 'purchase_request_id');
	}

	public function status()
	{
		return $this->belongsTo(\App\Models\Status::class);
	}

    public function createdBy()
    {
        return $this->belongsTo(\App\Models\Auth\User\User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(\App\Models\Auth\User\User::class, 'updated_by');
    }

	public function issued_docket_details()
	{
		return $this->hasMany(\App\Models\IssuedDocketDetail::class, 'header_id');
	}
}
