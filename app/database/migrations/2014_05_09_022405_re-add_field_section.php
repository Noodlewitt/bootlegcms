<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReAddFieldSection extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('content_default_settings', function($table) {
                //    $table->dropColumn('section');
                    $table->string('section')->default('Content');
                });
                
                Schema::table('content_settings', function($table) {
                //    $table->dropColumn('section');
                    $table->string('section')->default('Content');
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
