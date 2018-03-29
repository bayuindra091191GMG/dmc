<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateItemReceiptHeadersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('item_receipt_headers', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('code', 45)->nullable();
			$table->dateTime('date')->nullable();
			$table->integer('purchase_order_id')->nullable()->index('FK_receipt_headers_purchase_order_id_idx');
			$table->integer('warehouse_id')->nullable()->index('FK_receipt_headers_warehouse_id_idx');
			$table->string('delivery_order_vendor', 100)->nullable();
			$table->integer('status_id')->nullable()->index('FK_receipt_headers_statuses_status_id_idx');
			$table->integer('created_by')->nullable()->index('FK_receipt_headers_users_created_by_idx');
			$table->timestamps();
			$table->integer('updated_by')->nullable()->index('FK_receipt_headers_users_updated_by_idx');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('item_receipt_headers');
	}

}
