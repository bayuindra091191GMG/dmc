<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateIssuedDocketDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('issued_docket_details', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('header_id')->nullable()->index('FK_docket_details_docket_headers_header_id_idx');
			$table->integer('item_id')->nullable()->index('FK_docket_details_items_item_id_idx');
			$table->integer('machinery_id')->nullable()->index('FK_docket_details_machineries_machinery_id_idx');
			$table->integer('quantity')->nullable();
			$table->string('remarks', 250)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('issued_docket_details');
	}

}
