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
		Schema::create('content', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->string('slug');
			$table->integer('user_id');
			$table->integer('status');
			$table->string('package')->nullable();
			$table->string('view');
			$table->integer('application_id');
			$table->integer('language')->nullable();
			$table->integer('language_original_id');
			$table->integer('parent_id')->nullable();
			$table->integer('lft');
			$table->integer('rgt');
			$table->integer('depth');
			$table->timestamps();
			$table->string('identifier')->nullable();
			$table->softDeletes();
			$table->string('position');
			$table->string('edit_view')->default('contents.edit');
			$table->integer('template_id')->nullable();
			$table->string('edit_action')->nullable();
			$table->text('headers', 65535);
			$table->string('edit_package')->nullable();
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
