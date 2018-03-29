<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMaterialRequestHeadersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('material_request_headers', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('code', 45)->nullable();
			$table->integer('type')->nullable()->comment('1 = Default/Other
2 = Oil/Fuel
3 = Service');
			$table->integer('department_id')->nullable()->index('FK_mr_headers_departments_department_id_idx');
			$table->integer('machinery_id')->nullable()->index('FK_mr_headers_machineries_machinery_id_idx');
			$table->string('priority', 45)->nullable();
			$table->string('km', 45)->nullable();
			$table->string('hm', 45)->nullable();
			$table->integer('status_id')->nullable()->index('FK_mr_headers_statuses_status_id_idx');
			$table->dateTime('date')->nullable();
			$table->string('close_reason', 200)->nullable();
			$table->integer('closed_by')->nullable()->index('FK_mr_headers_users_closed_by_idx');
			$table->dateTime('closed_at')->nullable();
			$table->integer('created_by')->nullable()->index('FK_mr_headers_users_created_by_idx');
			$table->timestamps();
			$table->integer('updated_by')->nullable()->index('FK_mr_headers_users_updated_by_idx');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('material_request_headers');
	}

}
