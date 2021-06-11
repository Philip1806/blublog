<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlublogCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
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
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blublog_comments');
    }
}
