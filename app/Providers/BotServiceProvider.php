<?php

namespace App\Providers;

use App\Services\Bot\Commands;
use Illuminate\Support\ServiceProvider;
use App\Services\Bot\Helper;

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
        $this->app->singleton('BotHelper', function () {
            return new Helper;
        });
    }
}
