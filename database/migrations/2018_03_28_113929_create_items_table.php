<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('items', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('code', 45)->nullable();
			$table->string('name', 100)->nullable();
			$table->string('part_number', 45)->nullable();
			$table->integer('stock')->nullable();
			$table->float('value', 10, 0)->nullable();
			$table->integer('is_serial')->default(0);
			$table->string('uom', 45)->nullable();
			$table->integer('group_id')->nullable()->index('FK_items_groups_group_id_idx');
			$table->integer('warehouse_id')->nullable()->index('FK_items_warehouse_id_warehouses_idx');
			$table->string('machinery_type', 100)->nullable();
			$table->string('description', 200)->nullable();
			$table->integer('created_by')->nullable()->index('FK_items_created_by_users_idx');
			$table->timestamps();
			$table->integer('updated_by')->nullable()->index('FK_items_updated_by_users_idx');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('items');
	}

}
