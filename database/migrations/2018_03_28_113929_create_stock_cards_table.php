<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStockCardsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('stock_cards', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('item_id')->nullable()->index('FK_stock_cards_items_item_id_idx');
			$table->integer('warehouse_id')->nullable()->index('FK_stock_cards_warehouses_warehouse_id_idx');
			$table->string('flag', 45)->nullable();
			$table->integer('change')->nullable();
			$table->integer('stock')->nullable();
			$table->string('description', 200)->nullable();
			$table->integer('created_by')->nullable()->index('FK_stock_cards_users_created_by_idx');
			$table->timestamps();
			$table->integer('updated_by')->nullable()->index('FK_stock_cards_users_updated_by_idx');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('stock_cards');
	}

}
