<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlublogCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
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
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blublog_categories');
        Schema::dropIfExists('blublog_posts_categories');
    }
}
