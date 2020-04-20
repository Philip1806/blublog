<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlublogRateTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blublog_posts_ratings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('user_id')->default(0);
            $table->unsignedBigInteger('rating');
            $table->string('type', 250)->nullable();;
            $table->string('ip', 250);
            $table->timestamps();
            //$table->foreign('post_id')->references('id')->on('blublog_posts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blublog_posts_ratings');
    }
}
