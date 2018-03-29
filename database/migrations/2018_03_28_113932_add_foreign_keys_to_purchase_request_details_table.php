<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPurchaseRequestDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('purchase_request_details', function(Blueprint $table)
		{
			$table->foreign('item_id', 'FK_pr_details_items_item_id')->references('id')->on('items')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('header_id', 'FK_pr_details_pr_headers_header_id')->references('id')->on('purchase_request_headers')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('purchase_request_details', function(Blueprint $table)
		{
			$table->dropForeign('FK_pr_details_items_item_id');
			$table->dropForeign('FK_pr_details_pr_headers_header_id');
		});
	}

}
