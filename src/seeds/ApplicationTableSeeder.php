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
                'cms_package' => 'cms',
                'user_id' => '1',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
                'deleted_at' => NULL,
            ),
        ));
    }
}