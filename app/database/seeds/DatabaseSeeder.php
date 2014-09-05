<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();
		$this->command->info('Starting seed..');

		// $this->call('UserTableSeeder');
		$this->call('UsersTableSeeder');
		$this->command->info('User seeded');
		$this->call('RolesTableSeeder');
		$this->command->info('Role seeded');
		$this->call('PermissionsTableSeeder');
		$this->command->info('Permissions seeded');
		$this->call('ContentTableSeeder');
		$this->command->info('Content seeded');
		$this->call('ApplicationsTableSeeder');
		$this->command->info('Application seeded');
		$this->call('ApplicationUrlsTableSeeder');
		$this->command->info('App url seeded');
	}



}