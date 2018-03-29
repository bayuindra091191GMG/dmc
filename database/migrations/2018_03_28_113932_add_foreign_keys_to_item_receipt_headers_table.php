<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToItemReceiptHeadersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('item_receipt_headers', function(Blueprint $table)
		{
			$table->foreign('purchase_order_id', 'FK_receipt_headers_purchase_order_id')->references('id')->on('purchase_order_headers')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('status_id', 'FK_receipt_headers_statuses_status_id')->references('id')->on('statuses')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('created_by', 'FK_receipt_headers_users_created_by')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('updated_by', 'FK_receipt_headers_users_updated_by')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('warehouse_id', 'FK_receipt_headers_warehouse_id')->references('id')->on('warehouses')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('item_receipt_headers', function(Blueprint $table)
		{
			$table->dropForeign('FK_receipt_headers_purchase_order_id');
			$table->dropForeign('FK_receipt_headers_statuses_status_id');
			$table->dropForeign('FK_receipt_headers_users_created_by');
			$table->dropForeign('FK_receipt_headers_users_updated_by');
			$table->dropForeign('FK_receipt_headers_warehouse_id');
		});
	}

}
