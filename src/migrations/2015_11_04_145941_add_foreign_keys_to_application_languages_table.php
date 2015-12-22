<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToApplicationLanguagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('application_languages', function(Blueprint $table)
		{
			$table->foreign('application_id', 'FK_application_languages_applications')->references('id')->on('applications')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('application_languages', function(Blueprint $table)
		{
			$table->dropForeign('FK_application_languages_applications');
		});
	}

}
