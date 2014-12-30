<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTemplateLoopbackAndHeadersFields extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//loopback allows us to override the parent id's if we ever need a circular tree.
        Schema::table('template', function($table){
            $table->integer('loopback');
        });

        Schema::table('template', function($table){
            $table->text('headers');
        });

        Schema::table('content', function($table){
            $table->text('headers');
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
