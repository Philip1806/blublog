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
use Blublog\Blublog\Livewire\BlublogLogsTable;
use Blublog\Blublog\Livewire\BlublogCommentsTable;
use Blublog\Blublog\Livewire\BlublogAuthorChange;

use Blublog\Blublog\BlublogAdmin;
use Blublog\Blublog\BlublogPanel;

class BlublogServiceProvider extends ServiceProvider
{
    protected $commands = [
        'Blublog\Blublog\Commands\BlublogSetUp',
        'Blublog\Blublog\Commands\BlublogInstall',
        'Blublog\Blublog\Commands\BlublogSitemap',
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
            __DIR__ . '/Config/blublog.php'        => config_path('blublog.php'),
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
        \Livewire::component('blublog-logs-table', BlublogLogsTable::class);
        \Livewire::component('blublog-comments-table', BlublogCommentsTable::class);
        \Livewire::component('blublog-author-change', BlublogAuthorChange::class);
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

        Gate::define('blublog_edit_tags', function ($user, $tag) {
            if ($user->blublogRoles->first()->havePermission('edit-tags')) {
                return true;
            }
            if ($user->blublogRoles->first()->havePermission('moderate-tags-within_set_time')) {
                $current = \Carbon\Carbon::now();
                if (config('blublog.moderate-tags-within') > \Carbon\Carbon::parse($tag->created_at)->diffInHours($current)) {
                    return true;
                }
            }
            return false;
        });

        Gate::define('blublog_delete_tags', function ($user, $tag) {
            if ($user->blublogRoles->first()->havePermission('delete-tags')) {
                return true;
            }
            if ($user->blublogRoles->first()->havePermission('moderate-tags-within_set_time')) {
                $current = \Carbon\Carbon::now();
                if (config('blublog.moderate-tags-within') > \Carbon\Carbon::parse($tag->created_at)->diffInHours($current)) {
                    return true;
                }
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
        Gate::define('blublog_view_stats_posts', function ($user, $post) {
            if ($user->blublogRoles->first()->havePermission('post-stats')) {
                return true;
            }
            if ($user->blublogRoles->first()->havePermission('own-post-stats')) {
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
            if ($user->blublogRoles->first()->havePermission('edit-own-posts') and $post->user_id == $user->id) {
                return true;
            }
            if (!in_array($post->status, config('blublog.post_status'))) {
                return false;
            }
            $position = array_search($post->status, config('blublog.post_status'));
            $edit_rules = config('blublog.post_status_edit');
            if ($edit_rules[$position] == 2) {
                if ($user->blublogRoles->first()->havePermission('edit-' . $post->status)) {
                    return true;
                }
            } elseif ($edit_rules[$position] == 0) {
                return true;
            }
            return false;
        });

        // Comments
        Gate::define('blublog_edit_comments', function ($user, $comment) {
            if ($user->blublogRoles->first()->havePermission('edit-comments')) {
                return true;
            }
            if ($user->blublogRoles->first()->havePermission('edit-own-comments')) {
                if ($comment->author_id == $user->id) {
                    return true;
                }
            }
            if ($user->blublogRoles->first()->havePermission('moderate-comments-from-own-posts')) {
                if ($comment->post->user_id == $user->id) {
                    return true;
                }
            }
            return false;
        });

        Gate::define('blublog_delete_comments', function ($user, $comment) {
            if ($user->blublogRoles->first()->havePermission('delete-comments')) {
                return true;
            }
            if ($user->blublogRoles->first()->havePermission('delete-own-comments')) {
                if ($comment->author_id == $user->id) {
                    return true;
                }
            }
            return false;
        });
        Gate::define('blublog_approve_comments', function ($user, $comment) {
            if ($user->blublogRoles->first()->havePermission('approve-comments')) {
                return true;
            }
            if ($user->blublogRoles->first()->havePermission('moderate-comments-from-own-posts')) {
                if ($comment->post->user_id == $user->id) {
                    return true;
                }
            }
            return false;
        });
    }
}
