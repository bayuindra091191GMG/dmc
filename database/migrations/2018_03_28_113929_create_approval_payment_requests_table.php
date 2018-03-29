<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateApprovalPaymentRequestsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('approval_payment_requests', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('payment_request_id')->nullable()->index('FK_approval_payment_requests_payment_requests_payment_reque_idx');
			$table->integer('user_id')->nullable()->index('FK_approval_payment_requests_users_user_id_idx');
			$table->timestamps();
			$table->integer('created_by')->nullable()->index('FK_approval_payment_requests_users_created_by_idx');
			$table->integer('updated_by')->nullable()->index('FK_approval_payment_requests_users_updated_by_idx');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('approval_payment_requests');
	}

}
