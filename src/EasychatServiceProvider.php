<?php

namespace Ronanflavio\Easychat;

use Illuminate\Support\ServiceProvider;

class EasychatServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('easychat', function ($app) {
            return new Easychat;
        });
    }

    public function boot()
    {
        // loading the routes file
        require __DIR__ . '/Http/routes.php';

        // define the path for the view files
        $this->loadViewsFrom(__DIR__ . '/../views', 'easychat');

        // define the paths that will be publisheds
        $this->publishes([
            __DIR__.'/config' => config_path('packages/Ronanflavio/Easychat'),
            __DIR__.'/migrations' => base_path('migrations/Ronanflavio/Easychat'),
            __DIR__.'/../public' => public_path('packages/Ronanflavio/Easychat'),
        ]);
    }
}
