<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToTemplateTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('template', function(Blueprint $table)
		{
			$table->foreign('application_id', 'FK_template_applications')->references('id')->on('applications')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('user_id', 'FK_template_users')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('template', function(Blueprint $table)
		{
			$table->dropForeign('FK_template_applications');
			$table->dropForeign('FK_template_users');
		});
	}

}
