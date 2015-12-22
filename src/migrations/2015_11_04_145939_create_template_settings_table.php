<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTemplateSettingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('template_settings', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('template_id')->unsigned()->index('FK_template_settings_template');
			$table->string('name');
			$table->text('value', 65535);
			$table->string('field_type');
			$table->string('field_parameters');
			$table->string('section');
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
		Schema::drop('template_settings');
	}

}
