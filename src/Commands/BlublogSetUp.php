<?php

namespace Blublog\Blublog\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Blublog\Blublog\Models\Post;
use Blublog\Blublog\Models\Category;
use Blublog\Blublog\Models\Role;
use Blublog\Blublog\Models\RolePermission;
use Illuminate\Support\Facades\Cache;
use Exception;

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
        if (!class_exists(config('blublog.userModel'))) {
            $this->error('The path to User Model IS NOT correct!');
            $this->error('> You need to edit blublog config file with the path to your user model.');
            return 0;
        }
        if (!blublog_user_model()::first()) {
            $this->error('No user found!');
            $this->error('You need to have at least one user, so that we can give them a permission for the blog.');
            $this->error('> Create a user and try again.');
            return 0;
        }
        if (!method_exists(blublog_user_model()::first(), 'blublogPosts')) {
            $this->error('User model trait not added!');
            $this->error('You need to add use ManageBlublog to your user model.');
            $this->error('> See the installation instructions.');
            return 0;
        }
        if (!class_exists('Livewire\Component')) {
            $this->error('Livewire not found!');
            $this->error('> You can install it with composer require livewire/livewire');
            return 0;
        }
        try {
            Storage::disk(config('blublog.files_disk', 'blublog'));
        } catch (Exception $e) {
            $this->error('Looks like the setting files_disk in blublog confing is wrong. Did you add file disk for blublog?');
            $this->error('Not fatal error. You will not be able to upload images. Continuing with the installation.');
        }
        if (!RolePermission::first()) {
            $this->line("Adding permissions and admin role for blublog.");

            $admin_role = new Role;
            $admin_role->name = "Administrators";
            $admin_role->descr = "Blog Admin";
            $admin_role->save();


            foreach (config('blublog.default_permissions') as $addPermission) {
                $permission = new RolePermission();
                $permission->permission = $addPermission[0];
                $permission->value = $addPermission[1];
                $permission->section = $addPermission[2];
                $permission->permission_descr = $addPermission[3];
                $permission->save();

                $admin_role->permissions()->syncWithoutDetaching($permission->id);
                $admin_role->save();
            }
        }


        $this->line("Making first user a blublog admin.");
        $user = blublog_user_model()::first();
        $user->blublogRoles()->sync(1);

        $this->line("Start Seting Up First Category.");
        $category = new Category;
        $category->title = "BLUblog";
        $category->descr = "First category. You can edit it or delete it.";
        $category->slug = "blublog";
        $category->save();

        $this->line("Start Seting Up First Post.");
        $post = new Post;
        $post->user_id = blublog_user_model()::first()->id;
        $post->title = "Welcome to BLUblog!";
        $post->img = "photos/no-image.jpg";
        $post->seo_title = "Welcome to BLUblog!";
        $post->seo_descr = "Thanks for instaling BLUblog!";
        $post->content = "Thanks for instaling BLUblog! You can edit or delete this post. If you have any problems, see the documentation or write to office@blublog.info, or add new issue on github.<br>Do not forget to add blublog driver in filesystems.php from config folder.";
        $post->slug = "Welcome-to-BLUblog";
        $post->save();
        $post->categories()->sync(1);

        $this->line("Clear cache.");
        Cache::flush();

        $this->info("Everything should be ready now. Log in and visit /panel and /blog");
    }
}
