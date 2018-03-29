<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePreferenceCompaniesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('preference_companies', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->text('address')->nullable();
			$table->string('phone', 45)->nullable();
			$table->string('fax', 45)->nullable();
			$table->string('email', 45)->nullable();
			$table->integer('ppn')->nullable();
			$table->timestamps();
			$table->integer('created_by')->nullable();
			$table->integer('updated_by')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('preference_companies');
	}

}
