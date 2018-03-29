<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProtectionValidationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('protection_validations', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('user_id')->unique('unique_user');
			$table->timestamp('ttl')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
			$table->text('validation_result');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('protection_validations');
	}

}
