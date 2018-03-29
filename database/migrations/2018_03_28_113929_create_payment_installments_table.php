<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePaymentInstallmentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payment_installments', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('payment_request_id')->nullable()->index('FK_payment_installments_payment_requests_payment_request_id_idx');
			$table->float('amount', 10, 0)->nullable();
			$table->dateTime('payment_date')->nullable();
			$table->string('remark', 150)->nullable();
			$table->integer('status_id')->nullable()->index('FK_payment_installments_statuses_status_id_idx');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('payment_installments');
	}

}
