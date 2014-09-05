<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemplateSettings extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
            Schema::create('template_settings', function(Blueprint $table) {
                $table->increments('id');
                
                $table->string('name');
                $table->text('value');
                $table->string('field_type');
                $table->string('field_parameters');
                
                $table->string('section');
                
                $table->timestamps();
                $table->softDeletes();
            });

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('template_settings');
	}

}
