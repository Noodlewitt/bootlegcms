<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentsTable extends Migration {

    public function up()
    {
	Schema::table('contents', function(Blueprint $table){
            $table->engine = 'InnoDB';
            Schema::create('contents', function(Blueprint $t){
                $t->increments('id');
                $t->integer('parent_id')->unsigned()->nullable();
                $t->integer('position', false, true);
                $t->integer('real_depth', false, true);
                $t->softDeletes();

                $t->foreign('parent_id')->references('id')->on('contents');
            });
        });
    }

    public function down()
    {
        Schema::table('contents', function(Blueprint $table)
        {
            Schema::dropIfExists('contents');
        });
    }
}
