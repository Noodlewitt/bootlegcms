<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ContentTableMakeNullParentId extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::update(DB::raw('ALTER TABLE content MODIFY parent_id INT'));
		DB::update(DB::raw('ALTER TABLE content MODIFY language INT'));
		DB::update(DB::raw('ALTER TABLE content MODIFY identifier INT'));

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
