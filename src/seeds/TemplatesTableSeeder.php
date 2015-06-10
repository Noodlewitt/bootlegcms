<?php namespace Bootleg\Cms;
use \Illuminate\Database\Seeder;
use \Illuminate\Database\Eloquent\Model;

class TemplateTableSeeder extends Seeder {
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('template')->truncate();
        
        \DB::table('template')->insert(array (
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
                'rgt' => '2',
                'depth' => '0',
                'identifier' => '',
                'position' => '',
                'edit_action' => 'ContentsController@anyEdit',
                'edit_view' => 'contents.edit',
                'edit_package' => 'cms',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
                'deleted_at' => NULL,
                'auto_create' => '1',
            ),
        ));
    }
}