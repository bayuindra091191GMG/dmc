<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToIssuedDocketDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('issued_docket_details', function(Blueprint $table)
		{
			$table->foreign('header_id', 'FK_docket_details_docket_headers_header_id')->references('id')->on('issued_docket_headers')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('item_id', 'FK_docket_details_items_item_id')->references('id')->on('items')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('machinery_id', 'FK_docket_details_machineries_machinery_id')->references('id')->on('machineries')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('issued_docket_details', function(Blueprint $table)
		{
			$table->dropForeign('FK_docket_details_docket_headers_header_id');
			$table->dropForeign('FK_docket_details_items_item_id');
			$table->dropForeign('FK_docket_details_machineries_machinery_id');
		});
	}

}
