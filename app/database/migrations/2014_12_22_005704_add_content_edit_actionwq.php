<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddContentEditActionwq extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('content', function($table)
		{
		    $table->string('edit_action')->nullable();
		});
		$content = Content::all();
		foreach($content as $c){
			$c->edit_action = "ContentsController@anyEdit";
			$c->save();
		}
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
