<?php namespace Bootleg\Cms;
use \Illuminate\Database\Seeder;
use \Illuminate\Database\Eloquent\Model;

class ApplicationsTableSeeder extends Seeder {
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('applications')->truncate();
        
        \DB::table('applications')->insert(array (
            0 => 
            array (
                'id' => '1',
                'name' => 'newapplication',
                'parent_id' => '0',
                'theme_id' => '0',
                'cms_theme_id' => '0',
                'cms_service_provider' => 'Bootleg\\Cms\\CmsServiceProvider',
                'cms_package' => 'cms',
                'service_provider' => 'Bootleg\\Cms\\CmsServiceProvider',
                'package' => 'cms',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
                'deleted_at' => NULL,
            ),
        ));
    }
}