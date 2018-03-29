<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('employee_id')->nullable()->index('FK_users_employee_id_employees_idx');
			$table->string('name', 191);
			$table->string('username', 150)->nullable();
			$table->string('email', 191);
			$table->string('password', 191);
			$table->boolean('active')->default(1);
			$table->char('confirmation_code', 36)->nullable();
			$table->boolean('confirmed')->default(1);
			$table->string('remember_token', 100)->nullable();
			$table->integer('status_id')->default(1)->index('FK_users_status_id_status_idx');
			$table->timestamps();
			$table->integer('created_by')->nullable();
			$table->integer('updated_by')->nullable();
			$table->softDeletes();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
