<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPermissionDocumentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('permission_documents', function(Blueprint $table)
		{
			$table->foreign('document_id', 'FK_permission_documents_documents_id')->references('id')->on('documents')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('role_id', 'FK_permission_documents_roles_id')->references('id')->on('roles')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('created_by', 'FK_permission_documents_users_created_by')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('updated_by', 'FK_permission_documents_users_updated_by')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('permission_documents', function(Blueprint $table)
		{
			$table->dropForeign('FK_permission_documents_documents_id');
			$table->dropForeign('FK_permission_documents_roles_id');
			$table->dropForeign('FK_permission_documents_users_created_by');
			$table->dropForeign('FK_permission_documents_users_updated_by');
		});
	}

}
