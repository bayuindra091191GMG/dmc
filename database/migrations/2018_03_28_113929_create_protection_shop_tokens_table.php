<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProtectionShopTokensTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('protection_shop_tokens', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('user_id');
			$table->string('number', 191)->index();
			$table->timestamp('expires')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
			$table->string('success_url', 191);
			$table->string('cancel_url', 191);
			$table->string('success_url_title', 191);
			$table->string('cancel_url_title', 191);
			$table->string('shop_url', 191);
			$table->unique(['user_id','success_url','cancel_url'], 'pst_unique');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('protection_shop_tokens');
	}

}
