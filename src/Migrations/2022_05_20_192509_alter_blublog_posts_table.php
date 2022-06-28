<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterBlublogPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blublog_posts', function (Blueprint $table) {
            $table->dropColumn('img');
            $table->unsignedBigInteger('file_id')->nullable()->after('title');
        });
        Schema::table('blublog_images', function (Blueprint $table) {
            $table->boolean('is_video')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('blublog_posts', function (Blueprint $table) {
            $table->dropColumn('file_id');
        });
        Schema::table('blublog_images', function (Blueprint $table) {
            $table->dropColumn('is_video');
        });
    }
}
