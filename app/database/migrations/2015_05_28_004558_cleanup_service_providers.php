<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CleanupServiceProviders extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('applications', function($table)
		{
			$table->dropColumn('cms_theme_id');
			$table->dropColumn('cms_service_provider');
			$table->dropColumn('service_provider');
			$table->dropColumn('package');
			$table->dropColumn('theme_id');
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
