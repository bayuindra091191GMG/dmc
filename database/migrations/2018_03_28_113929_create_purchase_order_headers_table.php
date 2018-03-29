<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePurchaseOrderHeadersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('purchase_order_headers', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('code', 45)->nullable();
			$table->integer('purchase_request_id')->nullable()->index('FK_po_headers_pr_headers_purchase_request_id_idx');
			$table->integer('quotation_id')->nullable()->index('FK_po_headers_quot_headers_quotation_id_idx');
			$table->integer('supplier_id')->nullable()->index('FK_po_headers_suppliers_supplier_id_idx');
			$table->float('delivery_fee', 10, 0)->nullable();
			$table->float('total_discount', 10, 0)->nullable();
			$table->float('total_price', 10, 0)->nullable();
			$table->float('total_payment_before_tax', 10, 0)->nullable();
			$table->integer('pph_percent')->nullable();
			$table->integer('ppn_percent')->nullable();
			$table->float('pph_amount', 10, 0)->nullable();
			$table->float('ppn_amount', 10, 0)->nullable();
			$table->float('total_payment', 10, 0)->nullable();
			$table->integer('status_id')->nullable()->index('FK_po_headers_statuses_status_id_idx');
			$table->dateTime('date')->nullable();
			$table->integer('closed_by')->nullable()->index('FK_po_headers_users_closed_by_idx');
			$table->string('close_reason', 200)->nullable();
			$table->dateTime('closing_date')->nullable();
			$table->integer('created_by')->nullable()->index('FK_po_headers_users_created_by_idx');
			$table->timestamps();
			$table->integer('updated_by')->nullable()->index('FK_po_headers_users_updated_by_idx');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('purchase_order_headers');
	}

}
