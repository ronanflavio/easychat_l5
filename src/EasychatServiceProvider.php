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

        // define the files that will be publisheds
        $this->publishes([
            // __DIR__.'/config/config.php' => config_path('packages/Ronanflavio/Easychat/config.php'),
            // __DIR__.'/config/tables.php' => config_path('packages/Ronanflavio/Easychat/tables.php'),
            // __DIR__.'/migrations/2015_11_03_000000_create_ec_rooms_table.php' => config_path('packages/Ronanflavio/Easychat/2015_11_03_000000_create_ec_rooms_table.php'),
            // __DIR__.'/migrations/2015_11_03_000000_create_ec_server_messages_table.php' => config_path('packages/Ronanflavio/Easychat/2015_11_03_000000_create_ec_server_messages_table.php'),
            // __DIR__.'/migrations/2015_11_03_000000_create_ec_user_messages_table.php' => config_path('packages/Ronanflavio/Easychat/2015_11_03_000000_create_ec_user_messages_table.php'),
            __DIR__.'/config' => config_path('packages/Ronanflavio/Easychat'),
            // __DIR__.'/migrations' => config_path('packages/Ronanflavio/Easychat'),
            __DIR__.'/../public' => public_path('packages/Ronanflavio/Easychat'),
        ]);
    }
}
