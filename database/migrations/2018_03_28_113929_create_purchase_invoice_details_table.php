<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePurchaseInvoiceDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('purchase_invoice_details', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('header_id')->nullable()->index('FK_pi_details_pi_headers_header_id_idx');
			$table->integer('item_id')->nullable()->index('FK_pi_details_items_item_id_idx');
			$table->integer('quantity')->nullable();
			$table->float('price', 10, 0)->nullable();
			$table->integer('discount')->nullable();
			$table->float('subtotal', 10, 0)->nullable();
			$table->string('remark', 250)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('purchase_invoice_details');
	}

}
