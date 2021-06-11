<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlublogTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

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
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blublog_tags');
        Schema::dropIfExists('blublog_posts_tags');
    }
}
