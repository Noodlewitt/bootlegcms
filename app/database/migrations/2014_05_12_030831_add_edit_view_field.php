<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEditViewField extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
            Schema::table('content_default_fields', function($table) {
                $table->string('edit_view')->default('contents.form');
                $table->string('edit_service_provider')->default("Bootleg\\\Cms\\\CmsServiceProvider");
                $table->string('edit_package')->default('cms');
            });

            Schema::table('content', function($table) {
                $table->string('edit_view')->default('contents.form');
                $table->string('edit_service_provider')->default("Bootleg\\\Cms\\\CmsServiceProvider");
                $table->string('edit_package')->default('cms');
            });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
