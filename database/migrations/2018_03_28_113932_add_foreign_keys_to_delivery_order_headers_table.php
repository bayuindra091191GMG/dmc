<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToDeliveryOrderHeadersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('delivery_order_headers', function(Blueprint $table)
		{
			$table->foreign('machinery_id', 'FK_delivery_headers_machineries_machinery_id')->references('id')->on('machineries')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('purchase_request_id', 'FK_delivery_headers_pr_headers_purchase_request_id')->references('id')->on('purchase_request_headers')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('from_site_id', 'FK_delivery_headers_sites_from_site_id')->references('id')->on('sites')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('to_site_id', 'FK_delivery_headers_sites_to_site_id')->references('id')->on('sites')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('status_id', 'FK_delivery_headers_statuses_status_id')->references('id')->on('statuses')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('created_by', 'FK_delivery_headers_users_created_by')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('updated_by', 'FK_delivery_headers_users_updated_by')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('from_warehouse_id', 'FK_delivery_headers_warehouses_from_warehouse_id')->references('id')->on('warehouses')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('to_warehouse_id', 'FK_delivery_headers_warehouses_to_warehouse_id')->references('id')->on('warehouses')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('cancel_by', 'Fk_delivery_headers_users_cancel_by')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('confirm_by', 'Fk_delivery_headers_users_confirm_by')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('delivery_order_headers', function(Blueprint $table)
		{
			$table->dropForeign('FK_delivery_headers_machineries_machinery_id');
			$table->dropForeign('FK_delivery_headers_pr_headers_purchase_request_id');
			$table->dropForeign('FK_delivery_headers_sites_from_site_id');
			$table->dropForeign('FK_delivery_headers_sites_to_site_id');
			$table->dropForeign('FK_delivery_headers_statuses_status_id');
			$table->dropForeign('FK_delivery_headers_users_created_by');
			$table->dropForeign('FK_delivery_headers_users_updated_by');
			$table->dropForeign('FK_delivery_headers_warehouses_from_warehouse_id');
			$table->dropForeign('FK_delivery_headers_warehouses_to_warehouse_id');
			$table->dropForeign('Fk_delivery_headers_users_cancel_by');
			$table->dropForeign('Fk_delivery_headers_users_confirm_by');
		});
	}

}
