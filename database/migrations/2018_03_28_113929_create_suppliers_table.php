<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSuppliersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('suppliers', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('code', 45)->nullable();
			$table->string('name', 100)->nullable();
			$table->string('email', 45)->nullable();
			$table->string('phone', 45)->nullable();
			$table->string('fax', 45)->nullable();
			$table->string('cellphone', 45)->nullable();
			$table->string('contact_person', 45)->nullable();
			$table->string('address', 250)->nullable();
			$table->string('city', 45)->nullable();
			$table->string('remark', 45)->nullable();
			$table->string('npwp', 45)->nullable();
			$table->string('bank_name', 45)->nullable();
			$table->string('bank_account_number', 45)->nullable();
			$table->string('bank_account_name', 45)->nullable();
			$table->integer('created_by')->nullable()->index('FK_supplies_created_by_users_idx');
			$table->timestamps();
			$table->integer('updated_by')->nullable()->index('FK_supplies_updated_by_users_idx');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('suppliers');
	}

}
