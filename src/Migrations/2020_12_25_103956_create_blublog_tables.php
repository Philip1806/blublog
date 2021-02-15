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
            $table->string('slug', 250);
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('tag_id')->index()->nullable();
            $table->text('title');
            $table->string('img', 250)->nullable();
            $table->string('seo_descr', 250);
            $table->string('seo_title', 250);
            $table->longText('content');
            $table->text('excerpt')->nullable();
            $table->string('status', 50)->default('publish');
            $table->string('type', 50)->default('post');
            $table->boolean('comments')->default(1);
            $table->boolean('front')->default(0);
            $table->boolean('recommended')->default(0);
            $table->integer('likes')->default(0);
            $table->string('data', 250)->nullable();;
            $table->timestamps();
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
            $table->boolean('public')->default(0);
            $table->boolean('pinned')->default(0);
            $table->integer('author_id')->nullable()->index();
            $table->timestamps();
        });
        Schema::create('blublog_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('parent_id')->nullable()->index();
            $table->string('title', 250);
            $table->string('img', 250)->nullable();
            $table->string('descr', 250)->nullable();
            $table->string('slug', 250);
            $table->string('colorcode', 250)->nullable();
            $table->timestamps();
        });
        Schema::create('blublog_posts_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('category_id');
        });
        Schema::create('blublog_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('parent_id')->nullable()->index();
            $table->integer('post_id')->nullable()->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->string('size', 50)->nullable();
            $table->string('filename', 250);
            $table->timestamps();
        });

        Schema::create('blublog_tags', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug', 250);
            $table->string('title', 250);
            $table->string('img', 250)->nullable();
            $table->timestamps();
        });
        Schema::create('blublog_posts_tags', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('post_id')->index();
            $table->unsignedBigInteger('tag_id')->index();
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
        Schema::dropIfExists('blublog_posts_categories');
        Schema::dropIfExists('blublog_files');
        Schema::dropIfExists('blublog_pages');
        Schema::dropIfExists('blublog_tags');
        Schema::dropIfExists('blublog_posts_tags');
        Schema::dropIfExists('blublog_logs');
    }
}
