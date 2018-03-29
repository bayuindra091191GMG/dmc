<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDeliveryOrderHeadersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('delivery_order_headers', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('code', 45)->nullable();
			$table->integer('purchase_request_id')->nullable()->index('FK_delivery_headers_pr_headers_purchase_request_id_idx');
			$table->integer('from_site_id')->nullable()->index('FK_delivery_headers_sites_departure_site_id_idx');
			$table->integer('to_site_id')->nullable()->index('FK_delivery_headers_sites_arrival_site_id_idx');
			$table->integer('from_warehouse_id')->nullable()->index('FK_delivery_headers_warehouses_from_warehouse_id_idx');
			$table->integer('to_warehouse_id')->nullable()->index('FK_delivery_headers_warehouses_to_warehouse_id_idx');
			$table->integer('machinery_id')->nullable()->index('FK_delivery_headers_machineries_machinery_id_idx');
			$table->string('remark', 250)->nullable();
			$table->dateTime('date')->nullable();
			$table->integer('confirm_by')->nullable()->index('Fk_delivery_headers_users_confirm_by_idx');
			$table->dateTime('confirm_date')->nullable();
			$table->integer('cancel_by')->nullable()->index('Fk_delivery_headers_users_cancel_by_idx');
			$table->dateTime('cancel_date')->nullable();
			$table->integer('status_id')->nullable()->index('FK_delivery_headers_statuses_status_id_idx');
			$table->integer('created_by')->nullable()->index('FK_delivery_headers_users_created_by_idx');
			$table->timestamps();
			$table->integer('updated_by')->nullable()->index('FK_delivery_headers_users_updated_by_idx');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('delivery_order_headers');
	}

}
