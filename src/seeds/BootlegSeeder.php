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

        $this->call('Bootleg\Cms\ApplicationsTableSeeder');
        $this->call('Bootleg\Cms\ApplicationUrlsTableSeeder');
        $this->call('Bootleg\Cms\ContentTableSeeder');
        $this->call('Bootleg\Cms\PermissionsTableSeeder');
        $this->call('Bootleg\Cms\RolesTableSeeder');
        $this->call('Bootleg\Cms\TemplatesTableSeeder');
        $this->call('Bootleg\Cms\UsersTableSeeder');
    }

}
