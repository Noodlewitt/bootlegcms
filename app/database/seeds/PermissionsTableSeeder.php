<?php

class PermissionsTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('permissions')->truncate();
        
		\DB::table('permissions')->insert(array (
			0 => 
			array (
				'id' => '10',
				'requestor_id' => '1',
				'application_id' => '',
				'requestor_type' => 'role',
				'controller_id' => '*',
				'controller_type' => 'TemplateController@anyIndex',
				'x' => '1',
				'comment' => '',
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			1 => 
			array (
				'id' => '11',
				'requestor_id' => '1',
				'application_id' => '',
				'requestor_type' => 'role',
				'controller_id' => '*',
				'controller_type' => 'TemplateController@anyTree',
				'x' => '1',
				'comment' => '',
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			2 => 
			array (
				'id' => '12',
				'requestor_id' => '1',
				'application_id' => '',
				'requestor_type' => 'role',
				'controller_id' => '*',
				'controller_type' => 'ContentsController@anyIndex',
				'x' => '1',
				'comment' => '',
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			3 => 
			array (
				'id' => '13',
				'requestor_id' => '1',
				'application_id' => '',
				'requestor_type' => 'role',
				'controller_id' => '*',
				'controller_type' => 'ContentsController@anyTree',
				'x' => '1',
				'comment' => '',
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			4 => 
			array (
				'id' => '14',
				'requestor_id' => '1',
				'application_id' => '',
				'requestor_type' => 'role',
				'controller_id' => '*',
				'controller_type' => 'ContentsController@anyEdit',
				'x' => '1',
				'comment' => '',
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			5 => 
			array (
				'id' => '15',
				'requestor_id' => '1',
				'application_id' => '',
				'requestor_type' => 'role',
				'controller_id' => '*',
				'controller_type' => 'TemplateController@anyEdit',
				'x' => '1',
				'comment' => '',
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			6 => 
			array (
				'id' => '16',
				'requestor_id' => '1',
				'application_id' => '',
				'requestor_type' => 'role',
				'controller_id' => '*',
				'controller_type' => 'UsersController@anyDashboard',
				'x' => '1',
				'comment' => '',
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			7 => 
			array (
				'id' => '17',
				'requestor_id' => '1',
				'application_id' => '',
				'requestor_type' => 'role',
				'controller_id' => '*',
				'controller_type' => 'UsersController@anyIndex',
				'x' => '1',
				'comment' => '',
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			8 => 
			array (
				'id' => '18',
				'requestor_id' => '1',
				'application_id' => '',
				'requestor_type' => 'role',
				'controller_id' => '*',
				'controller_type' => 'UsersController@anyLogout',
				'x' => '1',
				'comment' => '',
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			9 => 
			array (
				'id' => '19',
				'requestor_id' => '1',
				'application_id' => '',
				'requestor_type' => 'role',
				'controller_id' => '*',
				'controller_type' => 'UsersController@anySettings',
				'x' => '1',
				'comment' => '',
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			10 => 
			array (
				'id' => '21',
				'requestor_id' => '1',
				'application_id' => '',
				'requestor_type' => 'role',
				'controller_id' => '*',
				'controller_type' => 'UsersController@anyCreate',
				'x' => '1',
				'comment' => '',
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			11 => 
			array (
				'id' => '23',
				'requestor_id' => '1',
				'application_id' => '',
				'requestor_type' => 'role',
				'controller_id' => '*',
				'controller_type' => 'UsersController@anyStore',
				'x' => '1',
				'comment' => '',
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			12 => 
			array (
				'id' => '25',
				'requestor_id' => '1',
				'application_id' => '',
				'requestor_type' => 'role',
				'controller_id' => '*',
				'controller_type' => 'UsersController@anyUpdate',
				'x' => '1',
				'comment' => '',
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			13 => 
			array (
				'id' => '27',
				'requestor_id' => '1',
				'application_id' => '',
				'requestor_type' => 'role',
				'controller_id' => '*',
				'controller_type' => 'TemplateController@anyCreate',
				'x' => '1',
				'comment' => '',
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			14 => 
			array (
				'id' => '28',
				'requestor_id' => '1',
				'application_id' => '',
				'requestor_type' => 'role',
				'controller_id' => '*',
				'controller_type' => 'ContentsController@anyStore',
				'x' => '1',
				'comment' => '',
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			15 => 
			array (
				'id' => '29',
				'requestor_id' => '1',
				'application_id' => '',
				'requestor_type' => 'role',
				'controller_id' => '*',
				'controller_type' => 'ContentsController@anyCreate',
				'x' => '1',
				'comment' => '',
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			16 => 
			array (
				'id' => '30',
				'requestor_id' => '1',
				'application_id' => '',
				'requestor_type' => 'role',
				'controller_id' => '*',
				'controller_type' => 'TemplateController@anyStore',
				'x' => '1',
				'comment' => '',
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			17 => 
			array (
				'id' => '31',
				'requestor_id' => '1',
				'application_id' => '',
				'requestor_type' => 'role',
				'controller_id' => '*',
				'controller_type' => 'TemplateController@anyUpdate',
				'x' => '1',
				'comment' => '',
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			18 => 
			array (
				'id' => '32',
				'requestor_id' => '1',
				'application_id' => '',
				'requestor_type' => 'role',
				'controller_id' => '*',
				'controller_type' => 'ContentsController@anyUpdate',
				'x' => '1',
				'comment' => '',
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			19 => 
			array (
				'id' => '33',
				'requestor_id' => '1',
				'application_id' => '',
				'requestor_type' => 'role',
				'controller_id' => '*',
				'controller_type' => 'ContentsController@anyDestroy',
				'x' => '1',
				'comment' => '',
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			20 => 
			array (
				'id' => '34',
				'requestor_id' => '1',
				'application_id' => '',
				'requestor_type' => 'role',
				'controller_id' => '*',
				'controller_type' => 'TemplateController@anyDestroy',
				'x' => '1',
				'comment' => '',
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			21 => 
			array (
				'id' => '35',
				'requestor_id' => '1',
				'application_id' => '',
				'requestor_type' => 'role',
				'controller_id' => '*',
				'controller_type' => 'TemplateController@deleteUpload',
				'x' => '1',
				'comment' => '',
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			22 => 
			array (
				'id' => '36',
				'requestor_id' => '1',
				'application_id' => '',
				'requestor_type' => 'role',
				'controller_id' => '*',
				'controller_type' => 'ContentsController@deleteUpload',
				'x' => '1',
				'comment' => '',
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			23 => 
			array (
				'id' => '37',
				'requestor_id' => '1',
				'application_id' => '',
				'requestor_type' => 'role',
				'controller_id' => '*',
				'controller_type' => 'ContentsController@postUpload',
				'x' => '1',
				'comment' => '',
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			24 => 
			array (
				'id' => '38',
				'requestor_id' => '1',
				'application_id' => '',
				'requestor_type' => 'role',
				'controller_id' => '*',
				'controller_type' => 'TemplateController@postUpload',
				'x' => '1',
				'comment' => '',
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			25 => 
			array (
				'id' => '39',
				'requestor_id' => '1',
				'application_id' => '',
				'requestor_type' => 'role',
				'controller_id' => '*',
				'controller_type' => 'ApplicationController@anyCreate',
				'x' => '1',
				'comment' => '',
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			26 => 
			array (
				'id' => '40',
				'requestor_id' => '1',
				'application_id' => '',
				'requestor_type' => 'role',
				'controller_id' => '*',
				'controller_type' => 'ApplicationController@postStore',
				'x' => '1',
				'comment' => '',
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			27 => 
			array (
				'id' => '41',
				'requestor_id' => '1',
				'application_id' => '',
				'requestor_type' => 'role',
				'controller_id' => '*',
				'controller_type' => 'ApplicationController@anySettings',
				'x' => '1',
				'comment' => '',
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			28 => 
			array (
				'id' => '42',
				'requestor_id' => '1',
				'application_id' => '',
				'requestor_type' => 'role',
				'controller_id' => '*',
				'controller_type' => 'ApplicationController@anyUpdate',
				'x' => '1',
				'comment' => '',
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			29 => 
			array (
				'id' => '43',
				'requestor_id' => '*',
				'application_id' => '',
				'requestor_type' => 'role',
				'controller_id' => '*',
				'controller_type' => 'RemindersController@getRemind',
				'x' => '1',
				'comment' => '',
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			30 => 
			array (
				'id' => '44',
				'requestor_id' => '*',
				'application_id' => '',
				'requestor_type' => 'role',
				'controller_id' => '*',
				'controller_type' => 'RemindersController@postRemind',
				'x' => '1',
				'comment' => '',
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			31 => 
			array (
				'id' => '45',
				'requestor_id' => '*',
				'application_id' => '',
				'requestor_type' => 'role',
				'controller_id' => '*',
				'controller_type' => 'RemindersController@getReset',
				'x' => '1',
				'comment' => '',
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			32 => 
			array (
				'id' => '46',
				'requestor_id' => '*',
				'application_id' => '',
				'requestor_type' => 'role',
				'controller_id' => '*',
				'controller_type' => 'RemindersController@postReset',
				'x' => '1',
				'comment' => '',
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			33 => 
			array (
				'id' => '48',
				'requestor_id' => '1',
				'application_id' => '',
				'requestor_type' => 'role',
				'controller_id' => '*',
				'controller_type' => 'ContentsController@anyFixtree',
				'x' => '1',
				'comment' => '',
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			34 => 
			array (
				'id' => '49',
				'requestor_id' => '*',
				'application_id' => '',
				'requestor_type' => 'role',
				'controller_id' => '*',
				'controller_type' => 'ReportsController@anyIndex',
				'x' => '1',
				'comment' => '',
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			35 => 
			array (
				'id' => '50',
				'requestor_id' => '*',
				'application_id' => '',
				'requestor_type' => 'role',
				'controller_id' => '*',
				'controller_type' => 'ReportsController@anyRun',
				'x' => '1',
				'comment' => '',
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			36 => 
			array (
				'id' => '51',
				'requestor_id' => '*',
				'application_id' => '',
				'requestor_type' => 'role',
				'controller_id' => '*',
				'controller_type' => 'ReportsController@anyEdit',
				'x' => '1',
				'comment' => '',
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
		));
	}

}
