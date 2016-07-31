<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\SlackClients\APIClient;
use App\Services\SlackClients\RTMClient;

class SlackClientServiceProvider extends ServiceProvider
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
        //
    }
}
