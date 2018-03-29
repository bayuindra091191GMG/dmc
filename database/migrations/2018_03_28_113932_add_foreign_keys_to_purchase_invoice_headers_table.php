<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPurchaseInvoiceHeadersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('purchase_invoice_headers', function(Blueprint $table)
		{
			$table->foreign('purchase_order_id', 'FK_pi_headers_po_headers_purchase_order_id')->references('id')->on('purchase_order_headers')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('status_id', 'FK_pi_headers_statuses_status_id')->references('id')->on('statuses')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('created_by', 'FK_pi_headers_users_created_by')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('updated_by', 'FK_pi_headers_users_updated_by')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('purchase_invoice_headers', function(Blueprint $table)
		{
			$table->dropForeign('FK_pi_headers_po_headers_purchase_order_id');
			$table->dropForeign('FK_pi_headers_statuses_status_id');
			$table->dropForeign('FK_pi_headers_users_created_by');
			$table->dropForeign('FK_pi_headers_users_updated_by');
		});
	}

}
