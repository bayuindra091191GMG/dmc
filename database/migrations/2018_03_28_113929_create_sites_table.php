<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSitesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sites', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('code', 45)->nullable();
			$table->string('name', 150)->nullable();
			$table->string('location', 50)->nullable();
			$table->string('phone', 45)->nullable();
			$table->string('pic', 45)->nullable();
			$table->timestamps();
			$table->integer('created_by')->nullable()->index('FK_sites_users_created_by_idx');
			$table->integer('updated_by')->nullable()->index('FK_sites_users_updated_by_idx');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('sites');
	}

}
