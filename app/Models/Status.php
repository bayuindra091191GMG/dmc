<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 20 Mar 2018 11:21:06 +0700.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Status
 * 
 * @property int $id
 * @property string $description
 * 
 * @property \Illuminate\Database\Eloquent\Collection $delivery_order_headers
 * @property \Illuminate\Database\Eloquent\Collection $employees
 * @property \Illuminate\Database\Eloquent\Collection $issued_docket_headers
 * @property \Illuminate\Database\Eloquent\Collection $item_receipt_headers
 * @property \Illuminate\Database\Eloquent\Collection $machineries
 * @property \Illuminate\Database\Eloquent\Collection $material_request_headers
 * @property \Illuminate\Database\Eloquent\Collection $payment_installments
 * @property \Illuminate\Database\Eloquent\Collection $payment_methods
 * @property \Illuminate\Database\Eloquent\Collection $purchase_invoice_headers
 * @property \Illuminate\Database\Eloquent\Collection $purchase_order_headers
 * @property \Illuminate\Database\Eloquent\Collection $purchase_request_headers
 * @property \Illuminate\Database\Eloquent\Collection $quotation_headers
 * @property \Illuminate\Database\Eloquent\Collection $users
 *
 * @package App\Models
 */
class Status extends Eloquent
{
	public $timestamps = false;

	protected $fillable = [
		'description'
	];

	public function delivery_order_headers()
	{
		return $this->hasMany(\App\Models\DeliveryOrderHeader::class);
	}

	public function employees()
	{
		return $this->hasMany(\App\Models\Employee::class);
	}

	public function issued_docket_headers()
	{
		return $this->hasMany(\App\Models\IssuedDocketHeader::class);
	}

	public function item_receipt_headers()
	{
		return $this->hasMany(\App\Models\ItemReceiptHeader::class);
	}

	public function machineries()
	{
		return $this->hasMany(\App\Models\Machinery::class);
	}

	public function material_request_headers()
	{
		return $this->hasMany(\App\Models\MaterialRequestHeader::class);
	}

	public function payment_installments()
	{
		return $this->hasMany(\App\Models\PaymentInstallment::class);
	}

	public function payment_methods()
	{
		return $this->hasMany(\App\Models\PaymentMethod::class);
	}

	public function purchase_invoice_headers()
	{
		return $this->hasMany(\App\Models\PurchaseInvoiceHeader::class);
	}

	public function purchase_order_headers()
	{
		return $this->hasMany(\App\Models\PurchaseOrderHeader::class);
	}

	public function purchase_request_headers()
	{
		return $this->hasMany(\App\Models\PurchaseRequestHeader::class);
	}

	public function quotation_headers()
	{
		return $this->hasMany(\App\Models\QuotationHeader::class);
	}

	public function users()
	{
		return $this->hasMany(\App\Models\Auth\User\User::class);
	}

    public function leaves()
    {
        return $this->hasMany(\App\Models\Leaf::class);
    }
}
