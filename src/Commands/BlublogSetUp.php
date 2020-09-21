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
        $this->info('BLUblog Firts Time Set Up');
        if (!User::first()) {
            $this->error('You first need to have at least one user! Create a user and try again.');
            return 0;
        }

        if (count(Setting::PhpCheck())) {
            $this->error('There is something wrong with PHP on this system. You will get more info in BLUblog panel.');
        }

        $this->line("Start Seting Up Roles.");
        // Start Seting Up Roles
        $per = BlublogUser::get_permissions();
        $admin_role = new Role;
        $mod_role = new Role;
        $author_role = new Role;
        for ($i = 0; $i < count($per); $i++) {
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
        // End Seting Up Roles

        $this->line("Start Seting Up Users.");
        // Start Seting Up Users
        $admin_id = $this->ask('ID of the user that will be admin? (0 if does not matter, make sure its correct no validation)');
        if ($admin_id) {
            BlublogUser::add(User::find($admin_id), 1);
        } else {
            $users = User::get();
            foreach ($users as $user) {
                BlublogUser::add($user, 1);
            }
        }
        // End Seting Up Users

        $this->line("Start Seting Up Settings.");
        Setting::set_default_settings();

        $this->line("Start Seting Up First Category.");
        $category = new Category;
        $category->title = "BLUblog";
        $category->descr = "First category. You can edit it or delete it.";
        $category->slug = "blublog";
        $category->colorcode = "rgb(" . rand(1, 255) . "," . rand(1, 255) . "," . rand(1, 255) . ");";
        $category->save();

        $this->line("Start Seting Up First Post.");
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

        $this->line("Clear cache.");
        Cache::flush();

        $this->info("Everything should be ready now. Log in and visit /panel and /blog");
    }
}
