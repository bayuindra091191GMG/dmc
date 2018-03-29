<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPaymentRequestsPiDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('payment_requests_pi_details', function(Blueprint $table)
		{
			$table->foreign('payment_requests_id', 'FK_payment_requests_pi_payment_requests')->references('id')->on('payment_requests')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('purchase_invoice_header_id', 'FK_payment_requests_pi_purchase_invoice_header')->references('id')->on('purchase_invoice_headers')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('payment_requests_pi_details', function(Blueprint $table)
		{
			$table->dropForeign('FK_payment_requests_pi_payment_requests');
			$table->dropForeign('FK_payment_requests_pi_purchase_invoice_header');
		});
	}

}
