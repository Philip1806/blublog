<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlublogSettingsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blublog_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 250);
            $table->text('val')->nullable(); // or binary
            $table->string('type', 50)->nullable();
            $table->timestamps();
        });

        Schema::create('blublog_posts_views', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('post_id');
            $table->string('ip', 250);
            $table->string('agent', 250);
            $table->text('data')->nullable();
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
        Schema::dropIfExists('blublog_settings');
    }
}
