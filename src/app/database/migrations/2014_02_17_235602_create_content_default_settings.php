<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateContentDefaultSettings extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('content_default_settings', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('content_type_id');
			$table->string('default_name');
			$table->text('default_value');
			$table->string('default_field_type');
			$table->string('default_field_parameters');
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
		Schema::drop('content_default_settings');
	}

}
