<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateContentSettingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('content_settings', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('content_id');
			$table->string('name');
			$table->text('value', 65535);
			$table->string('field_type')->nullable()->default('');
			$table->string('field_parameters')->nullable()->default('');
			$table->timestamps();
			$table->softDeletes();
			$table->string('section')->nullable()->default('');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('content_settings');
	}

}
