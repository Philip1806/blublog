<?php

namespace Blublog\Blublog\Commands;

use Illuminate\Console\Command;
use App\User;
use Blublog\Blublog\Models\BlublogUser;
use Blublog\Blublog\Models\Setting;
use Blublog\Blublog\Models\Post;
use Blublog\Blublog\Models\Category;
use Blublog\Blublog\Models\Role;
use Illuminate\Support\Facades\Cache;

class BlublogSetUp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blublog:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup BLUblog for first use.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (!BlublogUser::find(1)) {

            $per = array(
                'name', 'descr', 'create_posts', 'update_own_posts', 'delete_own_posts', 'view_stats_own_posts',
                'update_all_posts', 'delete_all_posts', 'view_stats_all_posts', 'posts_wait_for_approve', 'control_post_rating',
                'create_comments', 'moderate_comments_from_own_posts', 'moderate_own_comments', 'update_all_comments',
                'delete_all_comments', 'approve_comments_from_own_posts', 'approve_all_comments', 'ban_user_from_commenting',
                'create_tags', 'moderate_tags_created_within_set_time', 'update_all_tags', 'delete_all_tags',
                'view_categories', 'create_categories', 'update_categories', 'delete_categories', 'use_menu', 'view_pages',
                'create_pages', 'update_pages', 'delete_pages', 'create_users', 'update_own_user', 'update_all_users',
                'delete_users', 'change_settings', 'upload_files', 'delete_own_files', 'delete_all_files',
                'is_mod', 'is_admin'
            );
            $admin_role = new Role;
            $mod_role = new Role;
            $author_role = new Role;
            for ($i = 0; $i < 42; $i++) {
                if ($i == 0) {
                    $admin_role->name = "Administrator";
                    $mod_role->name = "Moderator";
                    $author_role->name = "Author";
                } elseif ($i == 1) {
                    $admin_role->descr = "Blog Admin";
                    $mod_role->descr = "Blog Moderator";
                    $author_role->descr = "Blog Author";
                } else {
                    $admin_role->{$per[$i]} = 1;
                    $mod_role->{$per[$i]} = 1;
                }
            }
            $mod_role->is_admin = 0;
            $mod_role->change_settings = 0;
            $mod_role->delete_users = 0;
            $mod_role->update_all_users = 0;
            $mod_role->create_users = 0;
            $mod_role->use_menu = 0;
            $mod_role->delete_categories = 0;
            $admin_role->timestamps = false;
            $mod_role->timestamps = false;
            $author_role->timestamps = false;
            if (!Role::first()) {
                $admin_role->save();
                $mod_role->save();
                $author_role->save();
            }


            $users = User::get();
            foreach ($users as $user) {
                BlublogUser::add($user, 1);
            }

            $category = new Category;
            $category->title = "BLUblog";
            $category->descr = "First category. You can edit it or delete it.";
            $category->slug = "blublog";
            $category->colorcode = "rgb(" . rand(1, 255) . "," . rand(1, 255) . "," . rand(1, 255) . ");";
            $category->save();

            $post = new Post;
            $post->user_id = BlublogUser::first()->id;
            $post->title = "Welcome to BLUblog!";
            $post->img = "no-img.png";
            $post->seo_title = "Welcome to BLUblog!";
            $post->seo_descr = "Thanks for instaling BLUblog!";
            $post->content = "Thanks for instaling BLUblog! You can edit or delete this post. If you have any problems, see the documentation or write to office@blublog.info, or add new issue on github.<br>Do not forget to add blublog driver in filesystems.php from config folder.";
            $post->slug = "Welcome-to-BLUblog";
            $post->categories()->sync([1 => ['post_id' => 1]], false);
            $post->save();

            Cache::flush();
        }
        Setting::set_default_settings();
    }
}
