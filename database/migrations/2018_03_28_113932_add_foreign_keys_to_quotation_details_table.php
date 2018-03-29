<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToQuotationDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('quotation_details', function(Blueprint $table)
		{
			$table->foreign('item_id', 'FK_quot_details_items_item_id')->references('id')->on('items')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('header_id', 'FK_quot_details_quot_headers_header_id')->references('id')->on('quotation_headers')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('quotation_details', function(Blueprint $table)
		{
			$table->dropForeign('FK_quot_details_items_item_id');
			$table->dropForeign('FK_quot_details_quot_headers_header_id');
		});
	}

}
