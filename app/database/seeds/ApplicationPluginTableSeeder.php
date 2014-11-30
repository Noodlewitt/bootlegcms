<?php

class ApplicationPluginTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('application_plugin')->truncate();
        
		\DB::table('application_plugin')->insert(array (
			0 => 
			array (
				'id' => '1',
				'application_id' => '1',
				'plugin_id' => '1',
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
		));
	}

}
