<?php namespace Bootleg\Cms;
use \Illuminate\Database\Seeder;
use \Illuminate\Database\Eloquent\Model;

class BootlegSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call('ApplicationsTableSeeder');
        $this->call('ApplicationUrlsTableSeeder');
        $this->call('ContentTableSeeder');
        $this->call('PermissionsTableSeeder');
        $this->call('RolesTableSeeder');
        $this->call('TemplatesTableSeeder');
        $this->call('UsersTableSeeder');
    }

}
