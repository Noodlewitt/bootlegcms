<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateApplicationPluginTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('application_plugin', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('application_id')->unsigned()->index('FK_application_plugin_applications');
			$table->integer('plugin_id')->unsigned()->index('FK_application_plugin_plugins');
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('application_plugin');
	}

}
