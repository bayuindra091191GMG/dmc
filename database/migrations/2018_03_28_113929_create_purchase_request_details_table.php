<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePurchaseRequestDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('purchase_request_details', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('header_id')->nullable()->index('FK_pr_details_pr_headers_header_id_idx');
			$table->integer('item_id')->nullable()->index('FK_pr_details_items_item_id_idx');
			$table->integer('quantity')->nullable();
			$table->integer('quantity_invoiced')->nullable();
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
		Schema::drop('purchase_request_details');
	}

}
