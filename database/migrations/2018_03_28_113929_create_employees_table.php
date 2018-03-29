<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEmployeesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('employees', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('code', 45)->nullable();
			$table->string('name', 100)->nullable();
			$table->string('email', 45)->nullable();
			$table->string('phone', 45)->nullable();
			$table->dateTime('date_of_birth')->nullable();
			$table->text('address')->nullable();
			$table->integer('department_id')->nullable()->index('FK_employess_department_id_departments_idx');
			$table->integer('site_id')->nullable()->index('FK_employees_sites_id_sites_idx');
			$table->integer('status_id')->index('FK_employees_status_id_statuses_idx');
			$table->integer('created_by')->nullable()->index('FK_employess_created_by_users_idx');
			$table->timestamps();
			$table->integer('updated_by')->nullable()->index('FK_employess_updated_by_users_idx');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('employees');
	}

}
