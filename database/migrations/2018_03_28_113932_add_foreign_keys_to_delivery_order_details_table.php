<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToDeliveryOrderDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('delivery_order_details', function(Blueprint $table)
		{
			$table->foreign('header_id', 'FK_delivery_details_delivery_headers_header_id')->references('id')->on('delivery_order_headers')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('item_id', 'FK_delivery_details_items_item_id')->references('id')->on('items')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('delivery_order_details', function(Blueprint $table)
		{
			$table->dropForeign('FK_delivery_details_delivery_headers_header_id');
			$table->dropForeign('FK_delivery_details_items_item_id');
		});
	}

}
