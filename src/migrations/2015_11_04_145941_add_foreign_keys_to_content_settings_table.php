<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToContentSettingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('content_settings', function(Blueprint $table)
		{
			$table->foreign('content_id', 'FK_content_settings_content')->references('id')->on('content')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('content_settings', function(Blueprint $table)
		{
			$table->dropForeign('FK_content_settings_content');
		});
	}

}
