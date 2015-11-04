<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToContentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('content', function(Blueprint $table)
		{
			$table->foreign('application_id', 'FK_content_applications')->references('id')->on('applications')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('user_id', 'FK_content_users')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('content', function(Blueprint $table)
		{
			$table->dropForeign('FK_content_applications');
			$table->dropForeign('FK_content_users');
		});
	}

}
