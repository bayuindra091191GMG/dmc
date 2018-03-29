<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMaterialRequestDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('material_request_details', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('header_id')->nullable()->index('FK_mr_details_mr_headers_header_id_idx');
			$table->integer('item_id')->nullable()->index('FK_mr_details_items_item_id_idx');
			$table->integer('quantity')->nullable();
			$table->integer('quantity_received')->nullable();
			$table->integer('quantity_issued')->nullable();
			$table->string('remark', 150)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('material_request_details');
	}

}
