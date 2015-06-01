<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ContentSettingsAllowNull extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement("/* 6:07:16 pm localhost */ ALTER TABLE `content_settings` CHANGE `field_parameters` `field_parameters` VARCHAR(255)  CHARACTER SET utf8  COLLATE utf8_unicode_ci  NULL  DEFAULT ''");
		DB::statement("/* 6:07:16 pm localhost */ ALTER TABLE `content_settings` CHANGE `section` `section` VARCHAR(255)  CHARACTER SET utf8  COLLATE utf8_unicode_ci  NULL  DEFAULT ''");
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
