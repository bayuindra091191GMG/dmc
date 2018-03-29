<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePermissionDocumentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('permission_documents', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('role_id')->nullable()->index('FK_permission_documents_roles_id_idx');
			$table->integer('document_id')->nullable()->index('FK_permission_documents_documents_id_idx');
			$table->integer('create')->nullable();
			$table->integer('update')->nullable();
			$table->integer('delete')->nullable();
			$table->integer('read')->nullable();
			$table->integer('print')->nullable();
			$table->timestamps();
			$table->integer('created_by')->nullable()->index('FK_permission_documents_users_created_by_idx');
			$table->integer('updated_by')->nullable()->index('FK_permission_documents_users_updated_by_idx');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('permission_documents');
	}

}
