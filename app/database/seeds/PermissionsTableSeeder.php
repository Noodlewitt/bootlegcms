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
				'id' => '1',
				'requestor_id' => '*',
				'requestor_type' => 'role',
				'controller_id' => '*',
				'controller_type' => 'content',
				'r' => '0',
				'w' => '0',
				'x' => '1',
				'comment' => 'Base level permission for content',
				'created_at' => '2014-05-30 03:14:03',
				'updated_at' => '2014-05-30 03:14:03',
			),
			1 => 
			array (
				'id' => '2',
				'requestor_id' => '*',
				'requestor_type' => 'role',
				'controller_id' => '*',
				'controller_type' => 'content',
				'r' => '0',
				'w' => '0',
				'x' => '1',
				'comment' => 'Base level permission for application',
				'created_at' => '2014-05-30 03:14:03',
				'updated_at' => '2014-05-30 03:14:03',
			),
			2 => 
			array (
				'id' => '3',
				'requestor_id' => '1',
				'requestor_type' => 'role',
				'controller_id' => '*',
				'controller_type' => 'applications',
				'r' => '1',
				'w' => '1',
				'x' => '1',
				'comment' => 'Superuser has all permissions on all applications',
				'created_at' => '2014-05-30 03:14:03',
				'updated_at' => '2014-05-30 03:14:03',
			),
			3 => 
			array (
				'id' => '4',
				'requestor_id' => '1',
				'requestor_type' => 'role',
				'controller_id' => '*',
				'controller_type' => 'content',
				'r' => '1',
				'w' => '1',
				'x' => '1',
				'comment' => 'Superuser has all permissions on all content',
				'created_at' => '2014-05-30 03:14:03',
				'updated_at' => '2014-05-30 03:14:03',
			),
			4 => 
			array (
				'id' => '5',
				'requestor_id' => '2',
				'requestor_type' => 'role',
				'controller_id' => '*',
				'controller_type' => 'content',
				'r' => '1',
				'w' => '1',
				'x' => '1',
				'comment' => 'Administrator has all permissions',
				'created_at' => '2014-05-30 03:14:03',
				'updated_at' => '2014-05-30 03:14:03',
			),
			5 => 
			array (
				'id' => '6',
				'requestor_id' => '2',
				'requestor_type' => 'role',
				'controller_id' => '*',
				'controller_type' => 'application',
				'r' => '1',
				'w' => '1',
				'x' => '1',
				'comment' => 'Administrator has all permissions',
				'created_at' => '2014-05-30 03:14:03',
				'updated_at' => '2014-05-30 03:14:03',
			),
		));
	}

}
