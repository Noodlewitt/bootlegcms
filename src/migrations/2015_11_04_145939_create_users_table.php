<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

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
			$table->integer('application_id')->unsigned()->nullable();
			$table->string('name');
			$table->string('surname');
			$table->string('state');
			$table->string('password');
			$table->string('email');
			$table->integer('role_id')->unsigned()->nullable()->index('FK_users_roles');
			$table->integer('status');
			$table->integer('is_guest');
			$table->integer('subscribe')->nullable();
			$table->string('remember_token', 100)->nullable();
			$table->string('default_language');
			$table->timestamps();
			$table->timestamp('loggedin_at')->default('0000-00-00 00:00:00');
			$table->softDeletes();
			$table->unique(['application_id','email'], 'application_id_email');
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
