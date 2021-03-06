<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 23 Mar 2018 15:42:43 +0700.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class MaterialRequestHeader
 * 
 * @property int $id
 * @property string $code
 * @property int $type
 * @property int $department_id
 * @property int $machinery_id
 * @property string $priority
 * @property string $km
 * @property string $hm
 * @property int $status_id
 * @property \Carbon\Carbon $date
 * @property string $close_reason
 * @property int $closed_by
 * @property \Carbon\Carbon $closed_at
 * @property int $created_by
 * @property \Carbon\Carbon $created_at
 * @property int $updated_by
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\Department $department
 * @property \App\Models\Machinery $machinery
 * @property \App\Models\Status $status
 * @property \App\Models\Auth\User\User $user
 * @property \Illuminate\Database\Eloquent\Collection $issued_docket_headers
 * @property \Illuminate\Database\Eloquent\Collection $material_request_details
 * @property \Illuminate\Database\Eloquent\Collection $purchase_request_headers
 *
 * @package App\Models
 */
class MaterialRequestHeader extends Eloquent
{
	protected $casts = [
		'type' => 'int',
		'department_id' => 'int',
		'machinery_id' => 'int',
		'status_id' => 'int',
		'closed_by' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $dates = [
		'date',
		'closed_at'
	];

    protected $appends = [
        'date_string'
    ];

	protected $fillable = [
		'code',
		'type',
		'department_id',
		'machinery_id',
		'priority',
		'km',
		'hm',
		'status_id',
		'date',
		'close_reason',
		'closed_by',
		'closed_at',
		'created_by',
		'updated_by'
	];

    public function scopeDateDescending(Builder $query){
        return $query->orderBy('date','DESC');
    }

    public function getDateStringAttribute(){
        return Carbon::parse($this->attributes['date'])->format('d M Y');
    }

	public function department()
	{
		return $this->belongsTo(\App\Models\Department::class);
	}

	public function machinery()
	{
		return $this->belongsTo(\App\Models\Machinery::class);
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

    public function closedBy()
    {
        return $this->belongsTo(\App\Models\Auth\User\User::class, 'closed_by');
    }

	public function issued_docket_headers()
	{
		return $this->hasMany(\App\Models\IssuedDocketHeader::class);
	}

	public function material_request_details()
	{
		return $this->hasMany(\App\Models\MaterialRequestDetail::class, 'header_id');
	}

	public function purchase_request_headers()
	{
		return $this->hasMany(\App\Models\PurchaseRequestHeader::class, 'material_request_id');
	}
}
