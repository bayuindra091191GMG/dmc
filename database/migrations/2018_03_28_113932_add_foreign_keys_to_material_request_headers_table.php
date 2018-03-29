<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToMaterialRequestHeadersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('material_request_headers', function(Blueprint $table)
		{
			$table->foreign('department_id', 'FK_mr_headers_departments_department_id')->references('id')->on('departments')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('machinery_id', 'FK_mr_headers_machineries_machinery_id')->references('id')->on('machineries')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('status_id', 'FK_mr_headers_statuses_status_id')->references('id')->on('statuses')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('closed_by', 'FK_mr_headers_users_closed_by')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('created_by', 'FK_mr_headers_users_created_by')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('updated_by', 'FK_mr_headers_users_updated_by')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('material_request_headers', function(Blueprint $table)
		{
			$table->dropForeign('FK_mr_headers_departments_department_id');
			$table->dropForeign('FK_mr_headers_machineries_machinery_id');
			$table->dropForeign('FK_mr_headers_statuses_status_id');
			$table->dropForeign('FK_mr_headers_users_closed_by');
			$table->dropForeign('FK_mr_headers_users_created_by');
			$table->dropForeign('FK_mr_headers_users_updated_by');
		});
	}

}
