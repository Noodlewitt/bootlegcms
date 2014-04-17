<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAccessPermissions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('access_permissions', function(Blueprint $table) {
			$table->increments('id');
			$table->string('action');
			$table->integer('allowed');
			$table->integer('accessor_id');
			$table->string('accessor_type');
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
		Schema::drop('access_permissions');
	}

}
