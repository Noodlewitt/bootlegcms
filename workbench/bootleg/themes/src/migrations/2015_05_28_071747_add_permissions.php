<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPermissions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		\DB::table('permissions')->insert(array (
			0 => array (
				'id' => '',
				'requestor_id' => '2',
				'application_id' => '',
				'requestor_type' => 'role',
				'controller_id' => '*',
				'controller_type' => 'Bootleg\Themes\ThemesController@anyIndex',
				'x' => '1',
				'comment' => '',
				'created_at' => date("Y-m-d H:i:s"),
				'updated_at' => date("Y-m-d H:i:s"),
			),
			1 => array (
				'id' => '',
				'requestor_id' => '2',
				'application_id' => '',
				'requestor_type' => 'role',
				'controller_id' => '*',
				'controller_type' => 'Bootleg\Themes\ThemesController@getEdit',
				'x' => '1',
				'comment' => '',
				'created_at' => date("Y-m-d H:i:s"),
				'updated_at' => date("Y-m-d H:i:s"),
			),
			2 => array (
				'id' => '',
				'requestor_id' => '2',
				'application_id' => '',
				'requestor_type' => 'role',
				'controller_id' => '*',
				'controller_type' => 'Bootleg\Themes\ThemesController@postEdit',
				'x' => '1',
				'comment' => '',
				'created_at' => date("Y-m-d H:i:s"),
				'updated_at' => date("Y-m-d H:i:s"),
			),
		));
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
