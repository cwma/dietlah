<?php

use Illuminate\Database\Migrations\Migration;

class CreateConversationsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('conversations', function ($tbl) {
            $tbl->increments('id');
            $tbl->integer('user_one')->references('id')->on('users')->onDelete('cascade');
            $tbl->integer('user_two')->references('id')->on('users')->onDelete('cascade');
            $tbl->boolean('status');
            $tbl->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('conversations');
    }
}
