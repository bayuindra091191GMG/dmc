<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePurchaseOrderDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('purchase_order_details', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('header_id')->nullable()->index('FK_po_details_po_headers_header_id_idx');
			$table->integer('item_id')->nullable()->index('FK_po_details_items_item_id_idx');
			$table->integer('quantity')->nullable();
			$table->integer('quantity_invoiced')->nullable();
			$table->integer('received_quantity')->nullable()->default(0);
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
		Schema::drop('purchase_order_details');
	}

}
