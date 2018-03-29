<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateApprovalRoleRulesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('approval_role_rules', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('document_id')->nullable();
			$table->text('description')->nullable();
			$table->integer('total_approval_users')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('approval_role_rules');
	}

}
