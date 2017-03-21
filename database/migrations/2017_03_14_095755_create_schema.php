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
	    $table->string('profile_pic')->nullable();
	    $table->string('bio')->nullable();
	});
	Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('image')->nullable();
            $table->string('title');
            $table->string('text', 10000);
            $table->string('location')->nullable();
	    $table->integer('likes_count')->unsigned();
	    $table->index('likes_count');
	    $table->integer('favourites_count')->unsigned();
	    $table->index('favourites_count');
	    $table->softDeletes();
            $table->timestamps();
        });
	Schema::create('user_posts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
	    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('post_id')->unsigned();
	    $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
        });
	Schema::create('comments', function (Blueprint $table) {
	    $table->increments('id');
	    $table->string('comment', 1000);
	    $table->softDeletes();
            $table->timestamps();
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
	    $table->string('tag_name')->unique();
	    $table->softDeletes();
            $table->timestamps();
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
            $table->timestamps();
	});
	Schema::create('likes', function (Blueprint $table) {
	    $table->increments('id');
            $table->integer('user_id')->unsigned();
	    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('post_id')->unsigned();
	    $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
	    $table->softDeletes();
            $table->timestamps();
	});
	Schema::create('reports', function (Blueprint $table) {
	    $table->increments('id');
            $table->integer('reported_id')->unsigned();
	    $table->string('report_type');
	    $table->string('report_comment');
	    $table->boolean('status');
	    $table->softDeletes();
            $table->timestamps();
	});
	Schema::create('tag_reports', function (Blueprint $table) {
	    $table->increments('id');
            $table->integer('tag_id')->unsigned();
	    $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
	    $table->integer('post_id')->unsigned();
	    $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
	    $table->string('report_comment');
	    $table->boolean('status');
	    $table->softDeletes();
            $table->timestamps();
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
	Schema::dropIfExists('tag_reports');
	Schema::dropIfExists('reports');
	Schema::dropIfExists('likes');
	Schema::dropIfExists('favourites');
	Schema::dropIfExists('post_tags');
	Schema::dropIfExists('tags');
	Schema::dropIfExists('post_comments');
	Schema::dropIfExists('comments');
	Schema::dropIfExists('user_posts');
	Schema::dropIfExists('posts');
    }
}
