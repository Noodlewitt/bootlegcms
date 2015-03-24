<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemplateTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('template', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name')->nullable();
			$table->string('slug')->nullable();
                        $table->integer('user_id');
                        $table->integer('status')->nullable();
                        $table->string('service_provider')->default("Bootleg\Cms\CmsServiceProvider")->nullable();;
                        $table->string('package')->default("cms")->nullable();;
                        $table->string('view')->default("default.view")->nullable();;
                        $table->string('layout')->default("default.layout")->nullable();;
                        
                        $table->integer('application_id')->nullable();
                        $table->string('language')->default('en');
                        $table->integer('language_original_id')->nullable();
                        
                        $table->integer('parent_id')->nullable();
                        $table->integer('lft')->nullable();
                        $table->integer('rgt')->nullable();
                        $table->integer('depth')->nullable();
                        
                        $table->string('identifier')->nullable();
                        $table->string('position')->nullable();
                        
                        $table->string('edit_view')->default("contents.form")->nullable();
                        $table->string('edit_service_provider')->default("Bootleg\Cms\CmsServiceProvider")->nullable();
                        $table->string('edit_package')->default("cms")->nullable();
                        
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
		Schema::dropIfExists('template');
	}

}
