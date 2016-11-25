<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBuildTags extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->increments('id')->unsigned()->index();
            $table->string('name', 32);
            $table->timestamps();
            $table->unique(['name']);
        });

        Schema::create('build_tag', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('build_id')->unsigned();
            $table->integer('tag_id')->unsigned();
            $table->unique(['build_id', 'tag_id']);
            $table->timestamps();
            
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
            $table->foreign('build_id')->references('id')->on('builds');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('build_tag');
        Schema::drop('tags');
    }
}
