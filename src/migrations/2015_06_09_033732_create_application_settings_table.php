<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateApplicationSettingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('application_settings', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('application_id');
			$table->string('name');
			$table->string('field_type');
			$table->string('field_parameters');
			$table->text('value', 65535);
			$table->timestamps();
			$table->string('section');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('application_settings');
	}

}
