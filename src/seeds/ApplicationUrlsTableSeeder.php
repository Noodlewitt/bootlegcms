<?php namespace Bootleg\Cms;
use \Illuminate\Database\Seeder;
use \Illuminate\Database\Eloquent\Model;

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
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
        ));
    }
}