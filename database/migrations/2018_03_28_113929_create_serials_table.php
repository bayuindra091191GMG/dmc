<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSerialsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('serials', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('item_id')->index('FK_serials_item_id_items_idx');
			$table->string('serial_number', 100)->nullable();
			$table->integer('warehouse_id')->nullable()->index('FK_serials_warehouse_id_warehouse_idx');
			$table->integer('machinery_id')->nullable()->index('FK_serials_machinery_id_machineries_idx');
			$table->text('description')->nullable();
			$table->integer('created_by')->nullable()->index('FK_serials_created_by_users_idx');
			$table->timestamps();
			$table->integer('updated_by')->nullable()->index('FK_serials_updated_by_users_idx');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('serials');
	}

}
