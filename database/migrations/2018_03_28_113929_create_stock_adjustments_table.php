<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStockAdjustmentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('stock_adjustments', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('item_id')->nullable()->index('FK_stock_adjustments_items_item_id_idx');
			$table->integer('depreciation')->nullable();
			$table->integer('warehouse_id')->nullable()->index('FK_stock_adjustments_warehouses_warehouse_id_idx');
			$table->integer('created_by')->nullable()->index('FK_stock_adjustment_users_created_by_idx');
			$table->timestamps();
			$table->integer('updated_by')->nullable()->index('FK_stock_adjustment_users_updated_by_idx');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('stock_adjustments');
	}

}
