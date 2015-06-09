<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBLUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('username');
			$table->string('password');
			$table->string('email')->unique();
			$table->integer('role_id');
			$table->integer('status');
			$table->dateTime('loggedin_at');
			$table->timestamps();
			$table->rememberToken();
			$table->softDeletes();
			$table->string('default_language');
			$table->dateTime('last_loggedin_at')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
