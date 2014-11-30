<?php

class ContentTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('content')->truncate();
        
		\DB::table('content')->insert(array (
			0 => 
			array (
				'id' => '1',
				'name' => 'Root',
				'slug' => '',
				'user_id' => '1',
				'status' => '1',
				'service_provider' => 'Bootleg\\Cms\\CmsServiceProvider',
				'package' => 'cms',
				'view' => 'default.view',
				'layout' => 'default.layout',
				'content_type_id' => '0',
				'application_id' => '1',
				'language' => '',
				'language_original_id' => '0',
				'parent_id' => NULL,
				'lft' => '1',
				'rgt' => '2',
				'depth' => '0',
				'created_at' => '2014-04-07 21:42:08',
				'updated_at' => '2014-05-20 01:26:19',
				'identifier' => 'home',
				'deleted_at' => NULL,
				'position' => '',
				'edit_view' => 'contents.form',
				'edit_service_provider' => 'Bootleg\\Cms\\CmsServiceProvider',
				'edit_package' => 'cms',
			),
			1 => 
			array (
				'id' => '2',
				'name' => 'home',
				'slug' => '/',
				'user_id' => '1',
				'status' => '1',
				'service_provider' => 'Bootleg\\Cms\\CmsServiceProvider',
				'package' => 'cms',
				'view' => 'default.view',
				'layout' => 'default.layout',
				'content_type_id' => '0',
				'application_id' => '1',
				'language' => '',
				'language_original_id' => '0',
				'parent_id' => '1',
				'lft' => '1',
				'rgt' => '2',
				'depth' => '0',
				'created_at' => '2014-06-18 05:58:10',
				'updated_at' => '2014-06-18 05:58:10',
				'identifier' => '',
				'deleted_at' => NULL,
				'position' => '',
				'edit_view' => 'contents.form',
				'edit_service_provider' => 'Bootleg\\Cms\\CmsServiceProvider',
				'edit_package' => 'cms',
			),
		));
	}

}
