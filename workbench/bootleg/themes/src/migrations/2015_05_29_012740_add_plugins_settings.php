<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPluginsSettings extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('plugin_settings', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->text('value');
			$table->text('field_type');
			$table->text('field_parameters');
			$table->integer('plugin_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
