<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToItemMutationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('item_mutations', function(Blueprint $table)
		{
			$table->foreign('from_warehouse_id', 'FK_item_mutations_from_warehouse_id_warehouses')->references('id')->on('warehouses')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('item_id', 'FK_item_mutations_item_id_items')->references('id')->on('items')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('to_warehouse_id', 'FK_item_mutations_to_warehouse_id_warehouses')->references('id')->on('warehouses')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('created_by', 'FK_items_mutations_created_by_users')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('updated_by', 'FK_items_mutations_updated_by_users')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('item_mutations', function(Blueprint $table)
		{
			$table->dropForeign('FK_item_mutations_from_warehouse_id_warehouses');
			$table->dropForeign('FK_item_mutations_item_id_items');
			$table->dropForeign('FK_item_mutations_to_warehouse_id_warehouses');
			$table->dropForeign('FK_items_mutations_created_by_users');
			$table->dropForeign('FK_items_mutations_updated_by_users');
		});
	}

}
