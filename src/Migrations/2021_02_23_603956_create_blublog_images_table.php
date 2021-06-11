<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlublogImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blublog_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('parent_id')->nullable()->index();
            $table->integer('post_id')->nullable()->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->string('size', 50)->nullable();
            $table->string('filename', 250);
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
        Schema::dropIfExists('blublog_images');
    }
}
