<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePluginSettingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('plugin_settings', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('plugin_id')->unsigned()->index('FK_plugin_settings_plugins');
			$table->integer('application_id')->unsigned()->index('FK_plugin_settings_applications');
			$table->string('name')->nullable();
			$table->string('value')->nullable();
			$table->string('field_type')->nullable();
			$table->string('field_parameters')->nullable();
			$table->timestamps();
			$table->softDeletes();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('plugin_settings');
	}

}
