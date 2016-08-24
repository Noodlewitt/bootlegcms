<?php namespace Bootleg\Cms;
use \Illuminate\Database\Seeder;
use \Illuminate\Database\Eloquent\Model;

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
                'username' => 'guest',
                'password' => '',
                'email' => '',
                'role_id' => '0',
                'status' => '0',
                'loggedin_at' => date("Y-m-d H:i:s"),
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
                'remember_token' => '',
                'deleted_at' => NULL,
            ),
            1 =>
            array (
                'username' => 'admin',
                'password' => \Hash::make('admin'),
                'email' => 'admin@admin.com',
                'role_id' => '2',
                'status' => '1',
                'loggedin_at' => date("Y-m-d H:i:s"),
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
                'remember_token' => '',
                'deleted_at' => NULL,
            ),
        ));
    }
}