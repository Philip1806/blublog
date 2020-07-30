<?php

namespace Blublog\Blublog;

use Blublog\Blublog\Models\BlublogUser;
use Illuminate\Support\ServiceProvider;
use Blublog\Blublog\Models\Post;
use Blublog\Blublog\Models\Comment;
use Blublog\Blublog\Models\Tag;
use Blublog\Blublog\Models\Category;
use Blublog\Blublog\Models\Page;
use Blublog\Blublog\Models\File;
use Blublog\Blublog\Policies\FilePolicy;
use Blublog\Blublog\Policies\PostPolicy;
use Blublog\Blublog\Policies\CommentPolicy;
use Blublog\Blublog\Policies\TagPolicy;
use Blublog\Blublog\Policies\CategoryPolicy;
use Blublog\Blublog\Policies\PagePolicy;
use Illuminate\Support\Facades\Gate;

class BlublogServiceProvider extends ServiceProvider
{
    protected $commands = [
        'Blublog\Blublog\Commands\BlublogSetUp',
        'Blublog\Blublog\Commands\BlublogSitemap',
    ];
    protected $policies = [
        Post::class => PostPolicy::class,
        Comment::class => CommentPolicy::class,
        Tag::class => TagPolicy::class,
        Category::class => CategoryPolicy::class,
        Page::class => PagePolicy::class,
        File::class => FilePolicy::class,
    ];
    public function registerPolicies()
    {
        foreach ($this->policies as $key => $value) {
            Gate::policy($key, $value);
        }
    }
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/views', 'blublog');
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
        $this->mergeConfigFrom(
            __DIR__.'/config/blublog.php', 'blublog'
        );
        $this->publishes([
            __DIR__.'/public' => public_path('/'),
            __DIR__.'/views/blublog' => base_path('resources/views/vendor/blublog/blublog'),
        ], 'public');
        $this->commands($this->commands);
        $file = __DIR__ . '/Models/Helpers.php';
        if (file_exists($file)) {
            require_once($file);
        }
        app('router')->aliasMiddleware('BlublogAdmin', \Blublog\Blublog\BlublogAdmin::class);
        app('router')->aliasMiddleware('BlublogUseMenu', \Blublog\Blublog\BlublogUseMenu::class);
        app('router')->aliasMiddleware('BlublogPanel', \Blublog\Blublog\BlublogPanel::class);


    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Gate::define('can_delete_all_files', function ($user) {
            $Blublog_User = BlublogUser::get_user($user);
            if($Blublog_User->user_role->delete_all_files){
                return true;
            }
            return false;
        });
        Gate::define('blublog_create_users', function ($user) {
            $Blublog_User = BlublogUser::get_user($user);
            if($Blublog_User->user_role->create_users){
                return true;
            }
            return false;
        });
        Gate::define('blublog_edit_users', function ($user) {
            $Blublog_User = BlublogUser::get_user($user);
            if($Blublog_User->user_role->update_all_users){
                return true;
            }
            return false;
        });
        Gate::define('blublog_delete_users', function ($user) {
            $Blublog_User = BlublogUser::get_user($user);
            if($Blublog_User->user_role->delete_users){
                return true;
            }
            return false;
        });
        $this->registerPolicies();
    }
}
