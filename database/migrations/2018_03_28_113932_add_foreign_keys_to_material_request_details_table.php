<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToMaterialRequestDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('material_request_details', function(Blueprint $table)
		{
			$table->foreign('item_id', 'FK_mr_details_items_item_id')->references('id')->on('items')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('header_id', 'FK_mr_details_mr_headers_header_id')->references('id')->on('material_request_headers')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('material_request_details', function(Blueprint $table)
		{
			$table->dropForeign('FK_mr_details_items_item_id');
			$table->dropForeign('FK_mr_details_mr_headers_header_id');
		});
	}

}
