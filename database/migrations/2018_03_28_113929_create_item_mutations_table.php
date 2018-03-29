<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateItemMutationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('item_mutations', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('item_id')->nullable()->index('FK_item_mutations_item_id_items_idx');
			$table->integer('from_warehouse_id')->nullable()->index('FK_item_mutations_warehouse_id_warehouses_idx');
			$table->integer('to_warehouse_id')->nullable()->index('FK_item_mutations_to_warehouse_id_warehouses_idx');
			$table->integer('mutation_quantity')->nullable();
			$table->integer('created_by')->nullable()->index('FK_items_mutations_created_by_users_idx');
			$table->timestamps();
			$table->integer('updated_by')->nullable()->index('FK_items_mutations_updated_by_users_idx');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('item_mutations');
	}

}
