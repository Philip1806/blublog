<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlublogTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blublog_posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->text('title');
            $table->string('headlight', 250)->nullable();
            $table->string('img', 250)->nullable();
            $table->string('seo_descr', 250);
            $table->string('seo_title', 250);
            $table->longText('content');
            $table->text('excerpt')->nullable();
            $table->string('status', 50)->default('publish');
            $table->string('type', 50)->default('post');
            $table->boolean('comments')->default(1);
            $table->boolean('slider')->default(0);
            $table->boolean('front')->default(0);
            $table->boolean('recommended')->default(0);
            $table->string('password', 255)->nullable();;
            $table->string('slug', 250);
            $table->string('video_url', 250)->nullable();;
            $table->unsignedBigInteger('tag_id')->nullable();;
            $table->timestamps();
            //$table->foreign('user_id')->references('id')->on('users');
        });
        Schema::create('blublog_comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 250);
            $table->string('email', 150);
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->text('body');
            $table->string('commentable_type', 250);
            $table->integer('commentable_id');
            $table->integer('likes')->default(0);
            $table->integer('dislikes')->default(0);
            $table->string('ip', 250)->nullable();
            $table->string('extra', 250)->nullable();
            $table->string('agent', 255)->nullable();
            $table->boolean('public')->default(0);
            $table->boolean('pinned')->default(0);
            $table->boolean('author')->default(0);
            $table->integer('author_id')->nullable();
            $table->timestamps();
        });
        Schema::create('blublog_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 250);
            $table->string('img', 250)->nullable();
            $table->string('descr', 250)->nullable();
            $table->string('slug', 250);
            $table->string('colorcode', 250)->nullable();
            $table->timestamps();
        });
        Schema::create('blublog_files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('size', 250)->nullable();
            $table->string('descr', 250);
            $table->string('filename', 250);
            $table->boolean('public')->default(1);
            $table->boolean('is_in_post')->default(0);
            $table->timestamps();
        });

        Schema::create('blublog_pages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug', 250);
            $table->string('title', 250);
            $table->string('img', 250)->nullable();
            $table->text('descr');
            $table->string('tags', 250)->nullable();
            $table->text('content');
            $table->string('sidebar', 50)->default(1);
            $table->boolean('public')->default(0);
            $table->timestamps();
        });

        Schema::create('blublog_tags', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug', 250);
            $table->string('title', 250);
            $table->string('img', 250)->nullable();
            $table->string('descr', 250)->nullable();
            $table->timestamps();
        });
        Schema::create('blublog_posts_tags', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('tag_id');
            //$table->foreign('post_id')->references('id')->on('blublog_posts');
            //$table->foreign('tag_id')->references('id')->on('blublog_tags');
        });

        Schema::create('blublog_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id')->default(0);
            $table->string('ip', 250)->nullable();
            $table->string('type', 150);
            $table->text('user_agent')->nullable();
            $table->text('request_url')->nullable();
            $table->text('referer')->nullable();
            $table->string('lang', 150)->nullable();
            $table->text('message');
            $table->text('data')->nullable();
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
        Schema::dropIfExists('blublog_posts');
        Schema::dropIfExists('blublog_comments');
        Schema::dropIfExists('blublog_categories');
        Schema::dropIfExists('blublog_files');
        Schema::dropIfExists('blublog_pages');
        Schema::dropIfExists('blublog_tags');
        Schema::dropIfExists('blublog_posts_tags');
        Schema::dropIfExists('blublog_logs');
    }
}
