<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPluginSettingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('plugin_settings', function(Blueprint $table)
		{
			$table->foreign('application_id', 'FK_plugin_settings_applications')->references('id')->on('applications')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('plugin_id', 'FK_plugin_settings_plugins')->references('id')->on('plugins')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('plugin_settings', function(Blueprint $table)
		{
			$table->dropForeign('FK_plugin_settings_applications');
			$table->dropForeign('FK_plugin_settings_plugins');
		});
	}

}
