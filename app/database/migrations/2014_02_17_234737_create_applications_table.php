<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateApplicationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('applications', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name');
                        $table->integer('parent_id');
                        $table->integer('theme_id');
                        $table->integer('cms_theme_id');
                        $table->string('cms_service_provider');
                        $table->string('cms_package');
                        $table->string('service_provider');
                        $table->string('package');
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
		Schema::drop('applications');
	}

}
