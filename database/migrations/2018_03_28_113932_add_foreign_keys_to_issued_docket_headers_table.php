<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToIssuedDocketHeadersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('issued_docket_headers', function(Blueprint $table)
		{
			$table->foreign('department_id', 'FK_docket_headers_departments_department_id')->references('id')->on('departments')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('unit_id', 'FK_docket_headers_machinery_unit_Id')->references('id')->on('machineries')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('material_request_header_id', 'FK_docket_headers_material_request_header_id')->references('id')->on('material_request_headers')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('status_id', 'FK_docket_headers_statuses_status_id')->references('id')->on('statuses')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('created_by', 'FK_docket_headers_users_created_by')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('updated_by', 'FK_docket_headers_users_updated_by')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('warehouse_id', 'FK_docket_headers_warehouse_id')->references('id')->on('warehouses')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('issued_docket_headers', function(Blueprint $table)
		{
			$table->dropForeign('FK_docket_headers_departments_department_id');
			$table->dropForeign('FK_docket_headers_machinery_unit_Id');
			$table->dropForeign('FK_docket_headers_material_request_header_id');
			$table->dropForeign('FK_docket_headers_statuses_status_id');
			$table->dropForeign('FK_docket_headers_users_created_by');
			$table->dropForeign('FK_docket_headers_users_updated_by');
			$table->dropForeign('FK_docket_headers_warehouse_id');
		});
	}

}
