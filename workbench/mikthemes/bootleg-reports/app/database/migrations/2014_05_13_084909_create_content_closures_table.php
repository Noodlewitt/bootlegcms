<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentClosuresTable extends Migration {

    public function up()
    {
        Schema::table('content_closure', function(Blueprint $table){
            $table->engine = 'InnoDB';
            Schema::create('content_closure', function(Blueprint $t){
                $t->increments('ctid');

                $t->integer('ancestor', false, true);
                $t->integer('descendant', false, true);
                $t->integer('depth', false, true);

                $t->foreign('ancestor')->references('id')->on('contents');
                $t->foreign('descendant')->references('id')->on('contents');
            });
        });
    }

    public function down()
    {
        Schema::table('content_closure', function(Blueprint $table)
        {
            Schema::dropIfExists('content_closure');
        });
    }
}
