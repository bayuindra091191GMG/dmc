<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPurchaseRequestHeadersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('purchase_request_headers', function(Blueprint $table)
		{
			$table->foreign('department_id', 'FK_pr_headers_departments_department_id')->references('id')->on('departments')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('machinery_id', 'FK_pr_headers_machineries_machinery_id')->references('id')->on('machineries')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('material_request_id', 'FK_pr_headers_mr_headers_material_request_id')->references('id')->on('material_request_headers')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('status_id', 'FK_pr_headers_statuses_status_id')->references('id')->on('statuses')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('closed_by', 'FK_pr_headers_users_closed_by')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('created_by', 'FK_pr_headers_users_created_by')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('updated_by', 'FK_pr_headers_users_updated_by')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('purchase_request_headers', function(Blueprint $table)
		{
			$table->dropForeign('FK_pr_headers_departments_department_id');
			$table->dropForeign('FK_pr_headers_machineries_machinery_id');
			$table->dropForeign('FK_pr_headers_mr_headers_material_request_id');
			$table->dropForeign('FK_pr_headers_statuses_status_id');
			$table->dropForeign('FK_pr_headers_users_closed_by');
			$table->dropForeign('FK_pr_headers_users_created_by');
			$table->dropForeign('FK_pr_headers_users_updated_by');
		});
	}

}
