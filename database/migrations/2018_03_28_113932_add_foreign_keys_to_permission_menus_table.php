<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPermissionMenusTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('permission_menus', function(Blueprint $table)
		{
			$table->foreign('menu_id', 'FK_permission_menu_menu_id')->references('id')->on('menus')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('role_id', 'FK_permission_menu_role_id')->references('id')->on('roles')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('permission_menus', function(Blueprint $table)
		{
			$table->dropForeign('FK_permission_menu_menu_id');
			$table->dropForeign('FK_permission_menu_role_id');
		});
	}

}
