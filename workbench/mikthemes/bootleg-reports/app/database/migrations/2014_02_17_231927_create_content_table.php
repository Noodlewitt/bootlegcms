<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateContentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('content', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->string('slug');
			$table->integer('user_id');
			$table->integer('status');
                        $table->string('service_provider')->nullable();
			$table->string('package')->nullable();
			$table->string('view');
			$table->string('layout');
			$table->integer('content_type_id');
			$table->integer('application_id');
			$table->string('language');
			$table->integer('language_original_id');
                        $table->integer('parent_id');
			$table->integer('lft');
			$table->integer('rgt');
			$table->integer('depth');
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
		Schema::drop('content');
	}

}
