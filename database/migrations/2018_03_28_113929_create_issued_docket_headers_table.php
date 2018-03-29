<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateIssuedDocketHeadersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('issued_docket_headers', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('code', 45)->nullable();
			$table->dateTime('date')->nullable();
			$table->string('km', 45)->nullable();
			$table->string('hm', 45)->nullable();
			$table->integer('warehouse_id')->nullable()->index('FK_docket_headers_warehouse_id_idx');
			$table->integer('material_request_header_id')->nullable()->index('FK_docket_headers_material_request_header_id_idx');
			$table->integer('unit_id')->nullable()->index('FK_docket_headers_machinery_unit_Id_idx');
			$table->integer('department_id')->nullable()->index('FK_docket_headers_departments_department_id_idx');
			$table->string('division', 100)->nullable();
			$table->integer('status_id')->nullable()->index('FK_docket_headers_statuses_status_id_idx');
			$table->integer('created_by')->nullable()->index('FK_docket_headers_users_created_by_idx');
			$table->timestamps();
			$table->integer('updated_by')->nullable()->index('FK_docket_headers_users_updated_by_idx');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('issued_docket_headers');
	}

}
