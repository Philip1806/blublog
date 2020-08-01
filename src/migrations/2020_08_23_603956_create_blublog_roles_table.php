<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlublogRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blublog_roles', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('name', 150);
            $table->string('descr', 250)->nullable();

            // Posts
            $table->boolean('create_posts')->default(1);
            $table->boolean('update_own_posts')->default(1);
            $table->boolean('delete_own_posts')->default(1);
            $table->boolean('view_stats_own_posts')->default(1);

            $table->boolean('update_all_posts')->default(0);
            $table->boolean('delete_all_posts')->default(0);
            $table->boolean('view_stats_all_posts')->default(1);

            $table->boolean('posts_wait_for_approve')->default(0);
            $table->boolean('control_post_rating')->default(0);

            // comments
            $table->boolean('create_comments')->default(1);
            $table->boolean('moderate_comments_from_own_posts')->default(1);
            $table->boolean('moderate_own_comments')->default(1);

            $table->boolean('update_all_comments')->default(0);
            $table->boolean('delete_all_comments')->default(0);

            $table->boolean('approve_comments_from_own_posts')->default(1);
            $table->boolean('approve_all_comments')->default(0);
            $table->boolean('ban_user_from_commenting')->default(0);

            // Tags tags
            $table->boolean('create_tags')->default(1);
            $table->boolean('moderate_tags_created_within_set_time')->default(0);

            $table->boolean('update_all_tags')->default(0);
            $table->boolean('delete_all_tags')->default(0);

            // Categories
            $table->boolean('view_categories')->default(0);
            $table->boolean('create_categories')->default(0);
            $table->boolean('update_categories')->default(0);
            $table->boolean('delete_categories')->default(0);

            // Menu
            $table->boolean('use_menu')->default(0);

            // Pages
            $table->boolean('view_pages')->default(0);
            $table->boolean('create_pages')->default(0);
            $table->boolean('update_pages')->default(0);
            $table->boolean('delete_pages')->default(0);

            // Users
            $table->boolean('create_users')->default(0);
            $table->boolean('update_own_user')->default(0);

            $table->boolean('update_all_users')->default(0);
            $table->boolean('delete_users')->default(0);

            // Files
            $table->boolean('upload_files')->default(0);
            $table->boolean('delete_own_files')->default(0);
            $table->boolean('delete_all_files')->default(0);

            // Settings
            $table->boolean('change_settings')->default(0);
            $table->boolean('is_admin')->default(0);
            $table->boolean('is_mod')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blublog_roles');
    }
}
