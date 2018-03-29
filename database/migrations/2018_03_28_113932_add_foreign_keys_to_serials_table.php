<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToSerialsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('serials', function(Blueprint $table)
		{
			$table->foreign('created_by', 'FK_serials_created_by_users')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('item_id', 'FK_serials_item_id_items')->references('id')->on('items')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('machinery_id', 'FK_serials_machinery_id_machineries')->references('id')->on('machineries')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('updated_by', 'FK_serials_updated_by_users')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('warehouse_id', 'FK_serials_warehouse_id_warehouse')->references('id')->on('warehouses')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('serials', function(Blueprint $table)
		{
			$table->dropForeign('FK_serials_created_by_users');
			$table->dropForeign('FK_serials_item_id_items');
			$table->dropForeign('FK_serials_machinery_id_machineries');
			$table->dropForeign('FK_serials_updated_by_users');
			$table->dropForeign('FK_serials_warehouse_id_warehouse');
		});
	}

}
