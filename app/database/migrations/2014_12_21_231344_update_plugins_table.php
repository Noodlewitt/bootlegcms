<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePluginsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$contents = Content::groupBy('service_provider')->get();
		foreach($contents as $content){
			$plugin = new Plugin();
			$plugin->service_provider = $content->service_provider;
			$plugin->package = $content->package;
			$plugin->name = $content->package;
			$plugin->save();
			$plugin->applications()->attach($content->application_id);
		}

		Schema::table('content', function($table)
		{
		    $table->dropColumn('service_provider');
		    //$table->dropColumn('package');
		    $table->dropColumn('edit_service_provider');
		    $table->dropColumn('edit_package');
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
