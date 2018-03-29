<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPurchaseOrderHeadersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('purchase_order_headers', function(Blueprint $table)
		{
			$table->foreign('purchase_request_id', 'FK_po_headers_pr_headers_purchase_request_id')->references('id')->on('purchase_request_headers')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('quotation_id', 'FK_po_headers_quot_headers_quotation_id')->references('id')->on('quotation_headers')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('status_id', 'FK_po_headers_statuses_status_id')->references('id')->on('statuses')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('supplier_id', 'FK_po_headers_suppliers_supplier_id')->references('id')->on('suppliers')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('closed_by', 'FK_po_headers_users_closed_by')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('created_by', 'FK_po_headers_users_created_by')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('updated_by', 'FK_po_headers_users_updated_by')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('purchase_order_headers', function(Blueprint $table)
		{
			$table->dropForeign('FK_po_headers_pr_headers_purchase_request_id');
			$table->dropForeign('FK_po_headers_quot_headers_quotation_id');
			$table->dropForeign('FK_po_headers_statuses_status_id');
			$table->dropForeign('FK_po_headers_suppliers_supplier_id');
			$table->dropForeign('FK_po_headers_users_closed_by');
			$table->dropForeign('FK_po_headers_users_created_by');
			$table->dropForeign('FK_po_headers_users_updated_by');
		});
	}

}
