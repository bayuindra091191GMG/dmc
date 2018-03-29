<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToNumberingSystemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('numbering_systems', function(Blueprint $table)
		{
			$table->foreign('doc_id', 'FK_numbering_system_documents_id')->references('id')->on('documents')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('numbering_systems', function(Blueprint $table)
		{
			$table->dropForeign('FK_numbering_system_documents_id');
		});
	}

}
