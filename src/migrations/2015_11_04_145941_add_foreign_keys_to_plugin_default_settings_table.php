<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPluginDefaultSettingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('plugin_default_settings', function(Blueprint $table)
		{
			$table->foreign('plugin_id', 'FK_plugin_default_settings_plugins')->references('id')->on('plugins')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('plugin_default_settings', function(Blueprint $table)
		{
			$table->dropForeign('FK_plugin_default_settings_plugins');
		});
	}

}
