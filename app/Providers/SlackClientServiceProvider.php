<?php

namespace App\Providers;

use App\Entities\User;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;
use Frlnc\Slack\Http\SlackResponseFactory;
use Frlnc\Slack\Http\CurlInteractor;
use Frlnc\Slack\Core\Commander;

class SlackClientServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application services.
     *
     * @param UserRepository $team
     * @return void
     */
    public function boot(UserRepository $team)
    {
            $this->app->singleton(Commander::class, function ($app) use ($team) {
                $interactor = new CurlInteractor;
                $interactor->setResponseFactory(new SlackResponseFactory);
                $token = $team->getTokenByMessengerId(Request::input('team_id'));
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

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [Commander::class];
    }
}
