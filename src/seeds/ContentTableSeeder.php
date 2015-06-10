<?php namespace Bootleg\Cms;
use \Illuminate\Database\Seeder;
use \Illuminate\Database\Eloquent\Model;

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
                'package' => 'cms',
                'view' => 'default.view',
                'application_id' => '1',
                'language' => '',
                'language_original_id' => '0',
                'parent_id' => NULL,
                'lft' => '1',
                'rgt' => '4',
                'depth' => '0',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
                'identifier' => 'home',
                'deleted_at' => NULL,
                'position' => '',
                'edit_view' => 'contents.form',
                'edit_package' => 'cms',
            ),
            1 => 
            array (
                'id' => '2',
                'name' => 'home',
                'slug' => '/',
                'user_id' => '1',
                'status' => '1',
                'package' => 'cms',
                'view' => 'default.view',
                'application_id' => '1',
                'language' => '',
                'language_original_id' => '0',
                'parent_id' => '1',
                'lft' => '2',
                'rgt' => '3',
                'depth' => '1',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
                'identifier' => '',
                'deleted_at' => NULL,
                'position' => '',
                'edit_view' => 'contents.form',
                'edit_package' => 'cms',
            ),
        ));
    }
}