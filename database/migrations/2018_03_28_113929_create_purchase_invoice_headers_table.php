<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePurchaseInvoiceHeadersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('purchase_invoice_headers', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('code', 45)->nullable();
			$table->integer('purchase_order_id')->nullable()->index('FK_pi_headers_po_headers_purchase_order_id_idx');
			$table->float('delivery_fee', 10, 0)->nullable();
			$table->float('total_discount', 10, 0)->nullable();
			$table->float('total_price', 10, 0)->nullable();
			$table->float('total_payment_before_tax', 10, 0)->nullable();
			$table->integer('pph_percent')->nullable();
			$table->integer('ppn_percent')->nullable();
			$table->float('pph_amount', 10, 0)->nullable();
			$table->float('ppn_amount', 10, 0)->nullable();
			$table->float('total_payment', 10, 0)->nullable();
			$table->dateTime('date')->nullable();
			$table->integer('status_id')->nullable()->index('FK_pi_headers_statuses_status_id_idx');
			$table->integer('created_by')->nullable()->index('FK_pi_headers_users_created_by_idx');
			$table->timestamps();
			$table->integer('updated_by')->nullable()->index('FK_pi_headers_users_updated_by_idx');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('purchase_invoice_headers');
	}

}
