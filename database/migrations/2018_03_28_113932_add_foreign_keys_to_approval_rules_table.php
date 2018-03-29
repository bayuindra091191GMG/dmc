<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToApprovalRulesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('approval_rules', function(Blueprint $table)
		{
			$table->foreign('created_by', 'FK_approval_rules_users_created_by')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('document_id', 'FK_approval_rules_users_document_id')->references('id')->on('documents')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('updated_by', 'FK_approval_rules_users_updated_by')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('user_id', 'FK_approval_rules_users_user_id')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('approval_rules', function(Blueprint $table)
		{
			$table->dropForeign('FK_approval_rules_users_created_by');
			$table->dropForeign('FK_approval_rules_users_document_id');
			$table->dropForeign('FK_approval_rules_users_updated_by');
			$table->dropForeign('FK_approval_rules_users_user_id');
		});
	}

}
