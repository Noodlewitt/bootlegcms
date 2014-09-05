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
				'id' => '1',
				'username' => 'admin',
				'password' => '$2y$10$S1JZ/o3GnV0BwgG1r/5/kOrUF71QnMWicAIJE8Sue7os2pWqypbmm',
				'email' => 'admin@admin.com',
				'role_id' => '1',
				'status' => '1',
				'loggedin_at' => '0000-00-00 00:00:00',
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
				'remember_token' => '',
				'deleted_at' => '0000-00-00 00:00:00',
			),
		));
	}

}
