<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DefaultFieldRename extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
            //renaming to remove daft default_ at the start - was a dumb idea.
            Schema::table('content_default_fields', function($table) {
                $table->renameColumn('default_name', 'name');
                $table->renameColumn('default_slug', 'slug');
                $table->renameColumn('default_package', 'package');
                $table->renameColumn('default_service_provider', 'service_provider');
                $table->renameColumn('default_view', 'view');
                $table->renameColumn('default_identifier', 'identifier');
                $table->renameColumn('default_layout', 'layout');
            });
            
            //renaming to remove daft default_ at the start - was a dumb idea.
            Schema::table('content_default_settings', function($table) {
                $table->renameColumn('default_name', 'name');
                $table->renameColumn('default_value', 'value');
                $table->renameColumn('default_field_type', 'field_type');
                $table->renameColumn('default_field_parameters', 'field_parameters');
                $table->string('section');
                $table->string('position');
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
