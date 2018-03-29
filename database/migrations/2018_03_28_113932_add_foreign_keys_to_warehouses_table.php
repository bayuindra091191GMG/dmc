<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToWarehousesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('warehouses', function(Blueprint $table)
		{
			$table->foreign('site_id', 'FK_warehouses_sites_site_id')->references('id')->on('sites')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('warehouses', function(Blueprint $table)
		{
			$table->dropForeign('FK_warehouses_sites_site_id');
		});
	}

}
