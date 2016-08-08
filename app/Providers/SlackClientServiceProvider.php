<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Frlnc\Slack\Http\SlackResponseFactory;
use Frlnc\Slack\Http\CurlInteractor;
use Frlnc\Slack\Core\Commander;

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
        $this->app->bind(Commander::class, function ($app) {
            $interactor = new CurlInteractor;
            $interactor->setResponseFactory(new SlackResponseFactory);
            $commander = new Commander('xoxp-some-token-for-slack', $interactor);

            return $commander;
        });
    }
}
