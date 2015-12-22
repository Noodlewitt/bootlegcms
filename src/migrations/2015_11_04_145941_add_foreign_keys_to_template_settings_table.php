<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToTemplateSettingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('template_settings', function(Blueprint $table)
		{
			$table->foreign('template_id', 'FK_template_settings_template')->references('id')->on('template')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('template_settings', function(Blueprint $table)
		{
			$table->dropForeign('FK_template_settings_template');
		});
	}

}
