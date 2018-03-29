<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToStockCardsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('stock_cards', function(Blueprint $table)
		{
			$table->foreign('item_id', 'FK_stock_cards_items_item_id')->references('id')->on('items')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('created_by', 'FK_stock_cards_users_created_by')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('updated_by', 'FK_stock_cards_users_updated_by')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('warehouse_id', 'FK_stock_cards_warehouses_warehouse_id')->references('id')->on('warehouses')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('stock_cards', function(Blueprint $table)
		{
			$table->dropForeign('FK_stock_cards_items_item_id');
			$table->dropForeign('FK_stock_cards_users_created_by');
			$table->dropForeign('FK_stock_cards_users_updated_by');
			$table->dropForeign('FK_stock_cards_warehouses_warehouse_id');
		});
	}

}
