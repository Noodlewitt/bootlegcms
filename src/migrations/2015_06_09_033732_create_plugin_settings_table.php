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
			$table->string('name');
			$table->text('value', 65535);
			$table->text('field_type', 65535);
			$table->text('field_parameters', 65535);
			$table->integer('application_plugin_id');
			$table->softDeletes();
			$table->timestamps();
			$table->integer('plugin_id')->nullable();
			$table->integer('application_id')->nullable();
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
