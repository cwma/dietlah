<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchema extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	Schema::table('users', function(Blueprint $table) {
	    $table->string('profile_pic');
	    $table->string('bio');
	});
	Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('image')->nullable();
            $table->string('title');
            $table->string('text');
            $table->string('location')->nullable();
	    $table->softDeletes();
            $table->timestamps();
        });
	Schema::create('user_posts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
	    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('post_id')->unsigned();
	    $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
            $table->timestamps();
        });
	Schema::create('comments', function (Blueprint $table) {
	    $table->increments('id');
	    $table->string('comment');
	    $table->softDeletes();
	});
	Schema::create('post_comments', function (Blueprint $table) {
	    $table->increments('id');
            $table->integer('user_id')->unsigned();
	    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('post_id')->unsigned();
	    $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
	});
	Schema::create('tags', function (Blueprint $table) {
	    $table->increments('id');
	    $table->string('tag_name');
	    $table->softDeletes();
	});
	Schema::create('post_tags', function (Blueprint $table) {
	    $table->increments('id');
            $table->integer('user_id')->unsigned();
	    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('post_id')->unsigned();
	    $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
            $table->integer('tag_id')->unsigned();
	    $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
	});
	Schema::create('favourites', function (Blueprint $table) {
	    $table->increments('id');
            $table->integer('user_id')->unsigned();
	    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('post_id')->unsigned();
	    $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
	    $table->softDeletes();
	});
	Schema::create('lists', function (Blueprint $table) {
	    $table->increments('id');
	    $table->string('list_name');
	    $table->softDeletes();
	});
	Schema::create('user_lists', function (Blueprint $table) {
	    $table->increments('id');
            $table->integer('user_id')->unsigned();
	    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('list_id')->unsigned();
	    $table->foreign('list_id')->references('id')->on('lists')->onDelete('cascade');
	});
	Schema::create('list_tags', function (Blueprint $table) {
	    $table->increments('id');
            $table->integer('list_id')->unsigned();
	    $table->foreign('list_id')->references('id')->on('lists')->onDelete('cascade');
            $table->integer('tag_id')->unsigned();
	    $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
	});
	Schema::create('reports', function (Blueprint $table) {
	    $table->increments('id');
            $table->integer('reported_id')->unsigned();
	    $table->string('report_type');
	    $table->string('report_comment');
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
        Schema::table('users', function(Blueprint $table) {
	    $table->dropColumn(['profile_pic', 'bio']);
	});
	Schema::dropIfExists('reports');
	Schema::dropIfExists('list_tags');
	Schema::dropIfExists('user_lists');
	Schema::dropIfExists('lists');
	Schema::dropIfExists('favourites');
	Schema::dropIfExists('post_tags');
	Schema::dropIfExists('tags');
	Schema::dropIfExists('post_comments');
	Schema::dropIfExists('comments');
	Schema::dropIfExists('user_posts');
	Schema::dropIfExists('posts');
    }
}
