<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToInterchangesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('interchanges', function(Blueprint $table)
		{
			$table->foreign('created_by', 'FK_interchange_created_by_user')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('item_id_after', 'FK_interchange_item_id_after_item')->references('id')->on('items')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('item_id_before', 'FK_interchange_item_id_before_item')->references('id')->on('items')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('interchanges', function(Blueprint $table)
		{
			$table->dropForeign('FK_interchange_created_by_user');
			$table->dropForeign('FK_interchange_item_id_after_item');
			$table->dropForeign('FK_interchange_item_id_before_item');
		});
	}

}
