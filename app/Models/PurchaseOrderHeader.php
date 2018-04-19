<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 23 Mar 2018 16:56:21 +0700.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class PurchaseOrderHeader
 * 
 * @property int $id
 * @property string $code
 * @property int $purchase_request_id
 * @property int $quotation_id
 * @property int $supplier_id
 * @property float $delivery_fee
 * @property float $total_discount
 * @property float $total_price
 * @property float $total_payment_before_tax
 * @property int $pph_percent
 * @property int $ppn_percent
 * @property float $pph_amount
 * @property float $ppn_amount
 * @property float $total_payment
 * @property int $status_id
 * @property int $is_approved
 * @property \Carbon\Carbon $approved_date
 * @property \Carbon\Carbon $date
 * @property int $closed_by
 * @property string $close_reason
 * @property \Carbon\Carbon $closing_date
 * @property int $created_by
 * @property \Carbon\Carbon $created_at
 * @property int $updated_by
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\PurchaseRequestHeader $purchase_request_header
 * @property \App\Models\QuotationHeader $quotation_header
 * @property \App\Models\Status $status
 * @property \App\Models\Supplier $supplier
 * @property \App\Models\User $user
 * @property \Illuminate\Database\Eloquent\Collection $item_receipt_headers
 * @property \Illuminate\Database\Eloquent\Collection $payment_requests_po_details
 * @property \Illuminate\Database\Eloquent\Collection $purchase_invoice_headers
 * @property \Illuminate\Database\Eloquent\Collection $purchase_order_details
 *
 * @package App\Models
 */
class PurchaseOrderHeader extends Eloquent
{
	protected $casts = [
		'purchase_request_id' => 'int',
		'quotation_id' => 'int',
		'supplier_id' => 'int',
		'delivery_fee' => 'float',
		'total_discount' => 'float',
		'total_price' => 'float',
		'total_payment_before_tax' => 'float',
		'pph_percent' => 'int',
		'ppn_percent' => 'int',
		'pph_amount' => 'float',
		'ppn_amount' => 'float',
		'total_payment' => 'float',
		'status_id' => 'int',
		'closed_by' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int'
	];

    protected $appends = [
        'total_price_string',
        'total_discount_string',
        'ppn_string',
        'pph_string',
        'total_payment_string',
        'delivery_fee_string',
        'date_string',
        'closing_date_string',
        'show_url',
        'show_url_pr',
        'supplier_name'
    ];

	protected $dates = [
		'date',
		'closing_date'
	];

	protected $fillable = [
		'code',
		'purchase_request_id',
		'quotation_id',
		'supplier_id',
		'delivery_fee',
		'total_discount',
		'total_price',
		'total_payment_before_tax',
		'pph_percent',
		'ppn_percent',
		'pph_amount',
		'ppn_amount',
		'total_payment',
		'status_id',
		'date',
        'is_approved',
        'approved_date',
		'closed_by',
		'close_reason',
		'closing_date',
		'created_by',
		'updated_by'
	];

    public function getClosingDateStringAttribute(){
        return Carbon::parse($this->attributes['closing_date'])->format('d M Y');
    }

    public function getDateStringAttribute(){
        return Carbon::parse($this->attributes['created_at'])->format('d M Y');
    }

    public function getTotalPriceStringAttribute(){
        return number_format($this->attributes['total_price'], 0, ",", ".");
    }

    public function getTotalDiscountStringAttribute(){
        if(!empty($this->attributes['total_discount']) && $this->attributes['total_discount'] != 0){
            return number_format($this->attributes['total_discount'], 0, ",", ".");
        }
        else{
            return '-';
        }
    }

    public function getPpnStringAttribute(){
        return number_format($this->attributes['ppn_amount'], 0, ",", ".");
    }

    public function getPphStringAttribute(){
        return number_format($this->attributes['pph_amount'], 0, ",", ".");
    }

    public function getTotalPaymentStringAttribute(){
        return number_format($this->attributes['total_payment'], 0, ",", ".");
    }

    public function getDeliveryFeeStringAttribute(){
        return number_format($this->attributes['delivery_fee'], 0, ",", ".");
    }

    public function getShowUrlAttribute(){
        return "<a style='text-decoration: underline;' href='". route('admin.purchase_orders.show', ['purchase_order' => $this->attributes['id']]). "'>". $this->attributes['code']. "</a>";
    }

    public function getShowUrlPrAttribute(){
        $prCode = $this->purchase_request_header->code;
        return "<a style='text-decoration: underline;' href='". route('admin.purchase_requests.show', ['purchase_request' => $this->attributes['purchase_request_id']]). "'>". $prCode. "</a>";
    }

    public function getSupplierNameAttribute(){
        return $this->supplier->name;
    }

    public function scopeDateDescending(Builder $query){
        return $query->orderBy('date','DESC');
    }

	public function purchase_request_header()
	{
		return $this->belongsTo(\App\Models\PurchaseRequestHeader::class, 'purchase_request_id');
	}

	public function quotation_header()
	{
		return $this->belongsTo(\App\Models\QuotationHeader::class, 'quotation_id');
	}

	public function status()
	{
		return $this->belongsTo(\App\Models\Status::class);
	}

	public function supplier()
	{
		return $this->belongsTo(\App\Models\Supplier::class);
	}

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class, 'updated_by');
	}

	public function item_receipt_headers()
	{
		return $this->hasMany(\App\Models\ItemReceiptHeader::class, 'purchase_order_id');
	}

	public function payment_requests_po_details()
	{
		return $this->hasMany(\App\Models\PaymentRequestsPoDetail::class, 'purchase_order_id');
	}

	public function purchase_invoice_headers()
	{
		return $this->hasMany(\App\Models\PurchaseInvoiceHeader::class, 'purchase_order_id');
	}

	public function purchase_order_details()
	{
		return $this->hasMany(\App\Models\PurchaseOrderDetail::class, 'header_id');
	}
}
