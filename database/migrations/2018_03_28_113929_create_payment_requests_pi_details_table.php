<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePaymentRequestsPiDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payment_requests_pi_details', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('payment_requests_id')->nullable()->index('FK_payment_requests_pi_payment_requests_idx');
			$table->integer('purchase_invoice_header_id')->nullable()->index('FK_payment_requests_pi_purchase_invoice_header_idx');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('payment_requests_pi_details');
	}

}
