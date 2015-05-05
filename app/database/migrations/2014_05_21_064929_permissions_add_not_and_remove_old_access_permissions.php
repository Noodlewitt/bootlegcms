<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PermissionsAddNotAndRemoveOldAccessPermissions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

		//and we no longer need this table. was a bad idea.
		Schema::table('access_permissions', function(Blueprint $table)
        {
            Schema::dropIfExists('access_permissions');
        });
        
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
