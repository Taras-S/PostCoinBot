<?php

namespace App\Providers;

use App\Entities\User;
use Illuminate\Support\Facades\Request;
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
        $this->app->bind(Commander::class, function ($app) {
            $interactor = new CurlInteractor;
            $interactor->setResponseFactory(new SlackResponseFactory);
            $token = User::select('bot_access_token')->where('messenger_id', Request::input('team_id'));
            return new Commander($token, $interactor);
        });
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
