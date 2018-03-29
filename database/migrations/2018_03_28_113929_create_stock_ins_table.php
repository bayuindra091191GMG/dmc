<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStockInsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('stock_ins', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('item_id')->nullable()->index('FK_stock_ins_items_item_id_idx');
			$table->integer('increase')->nullable();
			$table->integer('warehouse_id')->nullable()->index('FK_stock_ins_warehouses_warehouse_id_idx');
			$table->integer('created_by')->nullable()->index('FK_stock_ins_users_created_by_idx');
			$table->timestamps();
			$table->integer('updated_by')->nullable()->index('FK_stock_ins_users_updated_by_idx');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('stock_ins');
	}

}
