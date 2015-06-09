<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTemplateTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('template', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name')->nullable();
			$table->string('slug')->nullable();
			$table->integer('user_id');
			$table->integer('status')->nullable();
			$table->string('package')->nullable()->default('cms');
			$table->string('view')->nullable()->default('default.view');
			$table->integer('application_id')->nullable();
			$table->string('language')->default('en');
			$table->integer('language_original_id')->nullable();
			$table->integer('parent_id')->nullable();
			$table->integer('lft')->nullable();
			$table->integer('rgt')->nullable();
			$table->integer('depth')->nullable();
			$table->string('identifier')->nullable();
			$table->string('position')->nullable();
			$table->string('edit_view')->nullable()->default('contents.form');
			$table->string('edit_package')->nullable()->default('cms');
			$table->timestamps();
			$table->softDeletes();
			$table->integer('auto_create');
			$table->string('edit_action');
			$table->integer('loopback');
			$table->text('headers', 65535);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('template');
	}

}
