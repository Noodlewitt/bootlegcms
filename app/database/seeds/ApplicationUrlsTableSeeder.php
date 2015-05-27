<?php

class ApplicationUrlsTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('application_urls')->truncate();
        
		\DB::table('application_urls')->insert(array (
			0 => 
			array (
				'id' => '1',
				'application_id' => '1',
				'domain' => 'bootleg',
				'folder' => '/',
				'created_at' => date("Y-m-d H:i:s"),
				'updated_at' => date("Y-m-d H:i:s"),
			),
		));
	}

}
