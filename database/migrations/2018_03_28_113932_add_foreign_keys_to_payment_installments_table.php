<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPaymentInstallmentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('payment_installments', function(Blueprint $table)
		{
			$table->foreign('payment_request_id', 'FK_payment_installments_payment_requests_payment_request_id')->references('id')->on('payment_requests')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('status_id', 'FK_payment_installments_statuses_status_id')->references('id')->on('statuses')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('payment_installments', function(Blueprint $table)
		{
			$table->dropForeign('FK_payment_installments_payment_requests_payment_request_id');
			$table->dropForeign('FK_payment_installments_statuses_status_id');
		});
	}

}
