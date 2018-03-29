<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePermissionMenusTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('permission_menus', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('role_id')->nullable()->index('FK_permission_menu_role_id_idx');
			$table->integer('menu_id')->nullable()->index('FK_permission_menu_menu_id_idx');
			$table->timestamps();
			$table->integer('created_by')->nullable();
			$table->integer('updated_by')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('permission_menus');
	}

}
