<?php

class PluginsTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('plugins')->truncate();
        
		\DB::table('plugins')->insert(array (
			0 => 
			array (
				'id' => '1',
				'name' => 'default',
				'service_provider' => 'Bootleg\\Cms\\CmsServiceProvider',
				'package' => 'cms',
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
		));
	}

}
