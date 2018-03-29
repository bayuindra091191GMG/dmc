<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateQuotationHeadersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('quotation_headers', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('code', 45)->nullable();
			$table->integer('purchase_request_id')->nullable()->index('FK_quot_headers_pr_headers_purchase_request_id_idx');
			$table->integer('supplier_id')->nullable()->index('FK_quot_headers_suppliers_supplier_id_idx');
			$table->float('total_price', 10, 0)->nullable();
			$table->float('total_discount', 10, 0)->nullable();
			$table->float('total_payment', 10, 0)->nullable();
			$table->integer('status_id')->nullable()->index('FK_quot_headers_statuses_status_id_idx');
			$table->integer('created_by')->nullable()->index('FK_quot_headers_users_created_by_idx');
			$table->timestamps();
			$table->integer('updated_by')->nullable()->index('FK_quot_headers_users_updated_by_idx');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('quotation_headers');
	}

}
