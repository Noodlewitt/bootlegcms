<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateContentDefaultFieldsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('content_default_fields', function(Blueprint $table) {
			$table->increments('id');
			$table->string('default_name');
			$table->string('default_slug');
			$table->string('default_package');
                        $table->string('default_service_provider');
			$table->string('default_view');
			$table->string('default_layout');
			$table->integer('content_type_id');
			$table->string('default_identifier');
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
		Schema::drop('content_default_fields');
	}

}
