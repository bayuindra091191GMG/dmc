<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 20 Mar 2018 11:20:15 +0700.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Machinery
 * 
 * @property int $id
 * @property string $code
 * @property int $category_id
 * @property int $brand_id
 * @property string $type
 * @property string $sn_chasis
 * @property string $sn_engine
 * @property string $production_year
 * @property \Carbon\Carbon $purchase_date
 * @property string $location
 * @property string $description
 * @property int $status_id
 * @property int $created_by
 * @property \Carbon\Carbon $created_at
 * @property int $updated_by
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\User $user
 * @property \App\Models\MachineryBrand $machinery_brand
 * @property \App\Models\MachineryCategory $machinery_category
 * @property \App\Models\Status $status
 * @property \Illuminate\Database\Eloquent\Collection $delivery_order_headers
 * @property \Illuminate\Database\Eloquent\Collection $issued_docket_details
 * @property \Illuminate\Database\Eloquent\Collection $issued_docket_headers
 * @property \Illuminate\Database\Eloquent\Collection $material_request_headers
 * @property \Illuminate\Database\Eloquent\Collection $purchase_request_headers
 * @property \Illuminate\Database\Eloquent\Collection $serials
 *
 * @package App\Models
 */
class Machinery extends Eloquent
{
	protected $casts = [
		'category_id' => 'int',
		'brand_id' => 'int',
		'status_id' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $dates = [
		'purchase_date'
	];

    protected $appends = [
        'purchase_date_string'
    ];

	protected $fillable = [
		'code',
		'category_id',
		'brand_id',
		'type',
		'sn_chasis',
		'sn_engine',
		'production_year',
		'purchase_date',
		'location',
		'description',
		'status_id',
		'created_by',
		'updated_by'
	];

    public function getPurchaseDateStringAttribute(){
        if(!empty($this->attributes['purchase_date'])){
            return Carbon::parse($this->attributes['purchase_date'])->format('d M Y');
        }
        else return "-";
    }

    public function createdBy()
    {
        return $this->belongsTo(\App\Models\Auth\User\User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(\App\Models\Auth\User\User::class, 'updated_by');
    }

	public function machinery_brand()
	{
		return $this->belongsTo(\App\Models\MachineryBrand::class, 'brand_id');
	}

	public function machinery_category()
	{
		return $this->belongsTo(\App\Models\MachineryCategory::class, 'category_id');
	}

	public function status()
	{
		return $this->belongsTo(\App\Models\Status::class);
	}

	public function delivery_order_headers()
	{
		return $this->hasMany(\App\Models\DeliveryOrderHeader::class);
	}

	public function issued_docket_details()
	{
		return $this->hasMany(\App\Models\IssuedDocketDetail::class);
	}

	public function issued_docket_headers()
	{
		return $this->hasMany(\App\Models\IssuedDocketHeader::class, 'unit_id');
	}

	public function material_request_headers()
	{
		return $this->hasMany(\App\Models\MaterialRequestHeader::class);
	}

	public function purchase_request_headers()
	{
		return $this->hasMany(\App\Models\PurchaseRequestHeader::class);
	}

	public function serials()
	{
		return $this->hasMany(\App\Models\Serial::class);
	}
}
