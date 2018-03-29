<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToItemReceiptDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('item_receipt_details', function(Blueprint $table)
		{
			$table->foreign('item_id', 'FK_receipt_details_items_item_id')->references('id')->on('items')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('header_id', 'FK_receipt_details_receipt_headers_header_id')->references('id')->on('item_receipt_headers')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('item_receipt_details', function(Blueprint $table)
		{
			$table->dropForeign('FK_receipt_details_items_item_id');
			$table->dropForeign('FK_receipt_details_receipt_headers_header_id');
		});
	}

}
