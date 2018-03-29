<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateItemStocksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('item_stocks', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('item_id')->nullable()->index('FK_item_stocks_item_id_items_idx');
			$table->integer('warehouse_id')->nullable()->index('FK_item_stocks_warehouse_id_items_idx');
			$table->integer('stock')->nullable();
			$table->integer('created_by')->nullable()->index('FK_items_stocks_created_by_users_idx');
			$table->timestamps();
			$table->integer('updated_by')->nullable()->index('FK_items_stocks_updated_by_users_idx');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('item_stocks');
	}

}
