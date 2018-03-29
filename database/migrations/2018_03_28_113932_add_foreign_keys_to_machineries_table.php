<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToMachineriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('machineries', function(Blueprint $table)
		{
			$table->foreign('created_by', 'FK_machineries_created_by_users')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('brand_id', 'FK_machineries_machinery_brand_brand_id')->references('id')->on('machinery_brands')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('category_id', 'FK_machineries_machinery_category_category_id')->references('id')->on('machinery_categories')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('status_id', 'FK_machineries_statuses_status_id')->references('id')->on('statuses')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('updated_by', 'FK_machineries_updated_id_users')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('machineries', function(Blueprint $table)
		{
			$table->dropForeign('FK_machineries_created_by_users');
			$table->dropForeign('FK_machineries_machinery_brand_brand_id');
			$table->dropForeign('FK_machineries_machinery_category_category_id');
			$table->dropForeign('FK_machineries_statuses_status_id');
			$table->dropForeign('FK_machineries_updated_id_users');
		});
	}

}
