<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePaymentRequestsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payment_requests', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('code', 45)->nullable();
			$table->dateTime('date')->nullable();
			$table->string('type', 45)->nullable();
			$table->float('amount', 10, 0)->nullable();
			$table->float('ppn', 10, 0)->nullable();
			$table->float('pph_23', 10, 0)->nullable();
			$table->float('total_amount', 10, 0)->nullable();
			$table->string('requester_bank_name', 45)->nullable();
			$table->string('requester_bank_account', 45)->nullable();
			$table->string('requester_account_name', 45)->nullable();
			$table->integer('is_installment')->nullable();
			$table->string('note', 150)->nullable();
			$table->integer('status_id')->nullable()->index('FK_payment_requestst_statuses_status_id_idx');
			$table->integer('created_by')->nullable()->index('FK_payment_requestst_users_created_by_idx');
			$table->timestamps();
			$table->integer('updated_by')->nullable()->index('FK_payment_requestst_users_updated_by_idx');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('payment_requests');
	}

}
