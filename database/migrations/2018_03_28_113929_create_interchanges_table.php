<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInterchangesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('interchanges', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('item_id_before')->nullable()->index('FK_interchange_item_id_before_item_idx');
			$table->integer('item_id_after')->nullable()->index('FK_interchange_item_id_after_item_idx');
			$table->integer('created_by')->nullable()->index('FK_interchange_created_by_user_idx');
			$table->dateTime('created_at')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('interchanges');
	}

}
