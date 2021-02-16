<?php

namespace Blublog\Blublog;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Blublog\Blublog\Livewire\PostTable;
use Blublog\Blublog\Livewire\BlublogUsersTable;
use Blublog\Blublog\Livewire\BlublogEditRoles;
use Blublog\Blublog\Livewire\BlublogTags;
use Blublog\Blublog\Livewire\BlublogUploadFile;
use Blublog\Blublog\Livewire\BlublogImageSection;
use Blublog\Blublog\Livewire\BlublogCreateEditPost;
use Blublog\Blublog\Livewire\BlublogListImages;
use Blublog\Blublog\BlublogAdmin;
use Blublog\Blublog\BlublogPanel;


class BlublogServiceProvider extends ServiceProvider
{
    protected $commands = [
        'Blublog\Blublog\Commands\BlublogSetUp',
        'Blublog\Blublog\Commands\BlublogInstall',
    ];
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadRoutesFrom(__DIR__ . '/web.php');
        $this->loadViewsFrom(__DIR__ . '/Views', 'blublog');
        $this->loadMigrationsFrom(__DIR__ . '/Migrations');
        $this->mergeConfigFrom(
            __DIR__ . '/Config/blublog.php',
            'blublog'
        );
        app('router')->aliasMiddleware('BlublogAdmin', BlublogAdmin::class);
        app('router')->aliasMiddleware('BlublogPanel', BlublogPanel::class);
        $this->publish_files();
        $this->register_helpers();
        $this->commands($this->commands);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->register_componets();
        $this->define_gates();
    }

    public function publish_files()
    {
        $this->publishes([
            __DIR__ . '/public' => public_path('/'),
            __DIR__ . '/views' => base_path('resources/views/vendor/blublog'),
        ]);
    }
    public function register_helpers()
    {
        $file = __DIR__ . '/Helpers.php';
        if (file_exists($file)) {
            require_once($file);
        }
    }
    public function register_componets()
    {
        \Livewire::component('post-table', PostTable::class);
        \Livewire::component('blublog-users-table', BlublogUsersTable::class);
        \Livewire::component('blublog-edit-roles-perm', BlublogEditRoles::class);
        \Livewire::component('blublog-tags', BlublogTags::class);
        \Livewire::component('blublog-upload-img', BlublogUploadFile::class);
        \Livewire::component('blublog-img-section', BlublogImageSection::class);
        \Livewire::component('blublog-create-edit-post', BlublogCreateEditPost::class);
        \Livewire::component('blublog-list-images', BlublogListImages::class);
    }
    public function define_gates()
    {
        Gate::define('blublog_edit_users', function ($user, $edided) {
            if ($user->blublogRoles->first()->havePermission('edit-users')) {
                return true;
            }
            if ($user->blublogRoles->first()->havePermission('edit-profile')) {
                if ($user->id == $edided->id) {
                    return true;
                }
            }
            return false;
        });
        Gate::define('blublog_create_users', function ($user) {
            if ($user->blublogRoles->first()->havePermission('create-users')) {
                return true;
            }
            return false;
        });
        Gate::define('blublog_delete_users', function ($user) {
            if ($user->blublogRoles->first()->havePermission('delete-users')) {
                return true;
            }
            return false;
        });
        Gate::define('blublog_create_categories', function ($user) {
            if ($user->blublogRoles->first()->havePermission('create-categories')) {
                return true;
            }
            return false;
        });
        Gate::define('blublog_edit_categories', function ($user) {
            if ($user->blublogRoles->first()->havePermission('edit-categories')) {
                return true;
            }
            return false;
        });
        Gate::define('blublog_delete_categories', function ($user) {
            if ($user->blublogRoles->first()->havePermission('delete-categories')) {
                return true;
            }
            return false;
        });

        Gate::define('blublog_create_tags', function ($user) {
            if ($user->blublogRoles->first()->havePermission('create-tags')) {
                return true;
            }
            return false;
        });

        Gate::define('blublog_edit_tags', function ($user) {
            if ($user->blublogRoles->first()->havePermission('edit-tags')) {
                return true;
            }
            return false;
        });

        Gate::define('blublog_delete_tags', function ($user) {
            if ($user->blublogRoles->first()->havePermission('delete-tags')) {
                return true;
            }
            return false;
        });
        Gate::define('blublog_upload_files', function ($user) {
            if ($user->blublogRoles->first()->havePermission('upload-files')) {
                return true;
            }
            return false;
        });
        Gate::define('blublog_delete_files', function ($user, $file) {
            if ($user->blublogRoles->first()->havePermission('delete-files')) {
                return true;
            }
            if ($user->blublogRoles->first()->havePermission('delete-own-files')) {
                if ($file->user_id == $user->id) {
                    return true;
                }
            }
            return false;
        });

        // POSTS
        Gate::define('blublog_create_posts', function ($user) {
            if ($user->blublogRoles->first()->havePermission('create-posts')) {
                return true;
            }
            return false;
        });
        Gate::define('blublog_delete_posts', function ($user, $post) {
            if ($user->blublogRoles->first()->havePermission('delete-posts')) {
                return true;
            }
            if ($user->blublogRoles->first()->havePermission('delete-own-posts')) {
                if ($post->user_id == $user->id) {
                    return true;
                }
            }
            return false;
        });

        Gate::define('blublog_edit_post', function ($user, $post) {
            if ($user->blublogRoles->first()->havePermission('edit-posts')) {
                return true;
            }
            //TODO:Custom post status
            if ($user->blublogRoles->first()->havePermission('edit-own-posts') and $post->user_id == $user->id) {
                return true;
            }
            return false;
        });
    }
}
