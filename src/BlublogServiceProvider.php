<?php

namespace Philip1503\Blublog;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;


class BlublogServiceProvider extends ServiceProvider
{
    protected $commands = [
        'Philip1503\Blublog\Commands\BlublogSetUp',
        'Philip1503\Blublog\Commands\BlublogSitemap',
    ];
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
        ], 'public');
        $this->commands($this->commands);
        $file = __DIR__ . '/Models/Helpers.php';
        if (file_exists($file)) {
            require_once($file);
        }
        app('router')->aliasMiddleware('BlublogAdmin', \Philip1503\Blublog\BlublogAdmin::class);
        app('router')->aliasMiddleware('BlublogMod', \Philip1503\Blublog\BlublogMod::class);


    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

    }
}
