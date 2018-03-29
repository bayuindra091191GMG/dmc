<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNumberingSystemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('numbering_systems', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('doc_id')->nullable()->index('FK_numbering_system_documents_id_idx');
			$table->integer('next_no')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('numbering_systems');
	}

}
