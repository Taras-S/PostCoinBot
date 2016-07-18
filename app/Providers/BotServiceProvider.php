<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Bot\APIClient;
use App\Bot\RTMClient;
use App\Bot\SlashCommandResponser;

class BotServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('SlackAPI', function ($app) {
            return new APIClient('token'); // TODO: set current user token
        });

        $this->app->singleton('SlackChat', function ($app) {
            return new RTMClient('token'); // TODO: set current user token
        });

        $this->app->bind('BotCommand', function ($app) {
            return new SlashCommandResponser();
        });
    }
}
