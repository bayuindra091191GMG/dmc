<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToApprovalPaymentRequestsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('approval_payment_requests', function(Blueprint $table)
		{
			$table->foreign('payment_request_id', 'FK_approval_payment_requests_payment_requests_payment_request_id')->references('id')->on('payment_requests')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('created_by', 'FK_approval_payment_requests_users_created_by')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('updated_by', 'FK_approval_payment_requests_users_updated_by')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('user_id', 'FK_approval_payment_requests_users_user_id')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('approval_payment_requests', function(Blueprint $table)
		{
			$table->dropForeign('FK_approval_payment_requests_payment_requests_payment_request_id');
			$table->dropForeign('FK_approval_payment_requests_users_created_by');
			$table->dropForeign('FK_approval_payment_requests_users_updated_by');
			$table->dropForeign('FK_approval_payment_requests_users_user_id');
		});
	}

}
