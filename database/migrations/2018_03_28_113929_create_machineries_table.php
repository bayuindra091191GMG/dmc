<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMachineriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('machineries', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('code', 45)->nullable();
			$table->integer('category_id')->index('FK_machineries_machinery_category_category_id_idx');
			$table->integer('brand_id')->index('FK_machineries_machinery_brand_brand_id_idx');
			$table->string('type', 100)->nullable();
			$table->string('sn_chasis', 100)->nullable();
			$table->string('sn_engine', 100)->nullable();
			$table->string('production_year', 10)->nullable();
			$table->date('purchase_date')->nullable();
			$table->string('location', 45)->nullable();
			$table->string('description', 250)->nullable();
			$table->integer('status_id')->default(1)->index('FK_machineries_statuses_status_id_idx');
			$table->integer('created_by')->nullable()->index('FK_machineries_created_by_users_idx');
			$table->timestamps();
			$table->integer('updated_by')->nullable()->index('FK_machineries_updated_id_users_idx');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('machineries');
	}

}
