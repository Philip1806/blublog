<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlublogPostsTable extends Migration
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
            $table->integer('views')->default(0);
            $table->string('data', 250)->nullable();;
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
    }
}
