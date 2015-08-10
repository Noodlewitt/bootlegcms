<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTemplateTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('content_lang', function(Blueprint $table){
            $table->increments('id');
            $table->string('slug');
            $table->string('name');
            $table->integer('user_id');
            $table->integer('content_id')->nullable();
            $table->string('code')->nullable();

            $table->timestamps();
            $table->softDeletes();

        });

        Schema::create('content_settings_lang', function(Blueprint $table){
            $table->increments('id');
            $table->integer('content_id');
            $table->string('name');
            $table->text('value');
            $table->string('field_type')->nullable();
            $table->string('field_parameters')->nullable();
            $table->string('section');
            $table->integer('content_setting_id')->nullable();
            $table->string('code')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('template_lang', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('slug');
            $table->string('name');
            $table->integer('user_id');
            $table->integer('template_id')->nullable();
            $table->string('code')->nullable();

            $table->timestamps();
            $table->softDeletes();

        });

        Schema::create('template_settings_lang', function(Blueprint $table){
            $table->increments('id');
            $table->integer('template_id');
            $table->string('name');
            $table->text('value');
            $table->string('field_type')->nullable();;
            $table->string('field_parameters')->nullable();;
            $table->string('section');
            $table->integer('template_setting_id')->nullable();;
            $table->string('code')->nullable();;
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
        Schema::drop('template');
    }

}
