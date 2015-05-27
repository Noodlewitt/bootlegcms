<?php

class UsersTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('users')->truncate();
        
		\DB::table('users')->insert(array (
			0 => 
			array (
				'username' => 'guest',
				'password' => '',
				'email' => '',
				'role_id' => '1',
				'status' => '1',
				'loggedin_at' => date("Y-m-d H:i:s"),
				'created_at' => date("Y-m-d H:i:s"),
				'updated_at' => date("Y-m-d H:i:s"),
				'remember_token' => '',
				'deleted_at' => date("Y-m-d H:i:s"),
			),
			1 =>
			array (
				'username' => 'admin',
				'password' => '$2y$10$S1JZ/o3GnV0BwgG1r/5/kOrUF71QnMWicAIJE8Sue7os2pWqypbmm',
				'email' => 'admin@admin.com',
				'role_id' => '2',
				'status' => '1',
				'loggedin_at' => date("Y-m-d H:i:s"),
				'created_at' => date("Y-m-d H:i:s"),
				'updated_at' => date("Y-m-d H:i:s"),
				'remember_token' => '',
				'deleted_at' => date("Y-m-d H:i:s"),
			),
		));
	}

}
