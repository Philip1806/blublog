<?php

namespace Blublog\Blublog\Commands;
use Illuminate\Console\Command;
use App\User;
use Blublog\Blublog\Models\BlublogUser;
use Blublog\Blublog\Models\Setting;
use Blublog\Blublog\Models\Post;
use Blublog\Blublog\Models\Category;
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
        if(!BlublogUser::find(1)){
            $users = User::get();
            foreach ($users as $user){
                $Blublog_User = new BlublogUser;
                $Blublog_User->user_id = $user->id;
                $Blublog_User->role = "Administrator";
                $Blublog_User->save();
            }

            BlublogUser::first();
            $category = new Category;
            $category->title = "BLUblog";
            $category->descr = "First category. You can edit it or delete it.";
            $category->slug = "blublog";
            $category->colorcode = "rgb(". rand(1,255) . "," . rand(1,255) . "," . rand(1,255) . ");";
            $category->save();

            $post = new Post;
            $post->user_id = BlublogUser::first()->user_id;
            $post->title = "Welcome to BLUblog!";
            $post->img = "no-img.png";
            $post->seo_title = "Welcome to BLUblog!";
            $post->seo_descr = "Thanks for instaling BLUblog!";
            $post->content = "Thanks for instaling BLUblog! You can edit or delete this post. If you have any problems, see the documentation or write to office@blublog.info, or add new issue on github.<br>Do not forget to add blublog driver in filesystems.php from config folder.";
            $post->slug = "Welcome-to-BLUblog";
            $post->categories()->sync([1 => ['post_id' => 1]],false);
            $post->save();

            Cache::flush();
        }
        Setting::set_default_settings();
    }
}
