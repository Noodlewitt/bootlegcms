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
			$table->integer('user_id')->unsigned()->index('FK_content_users');
			$table->integer('application_id')->unsigned()->index('FK_content_applications');
			$table->string('name');
			$table->string('slug');
			$table->integer('status');
			$table->string('package')->nullable();
			$table->string('view');
			$table->integer('language')->nullable();
			$table->integer('language_original_id');
			$table->integer('parent_id')->nullable();
			$table->integer('lft');
			$table->integer('rgt');
			$table->integer('depth');
			$table->string('position');
			$table->string('identifier')->nullable();
			$table->integer('template_id')->nullable();
			$table->string('edit_view')->default('contents.edit');
			$table->string('edit_action')->nullable()->default('\Bootleg\Cms\ContentsController@anyEdit');
			$table->text('headers', 65535);
			$table->timestamps();
			$table->softDeletes();
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
