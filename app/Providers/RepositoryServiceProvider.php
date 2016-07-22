<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\SendingRepository;
use App\Repositories\SendingRepositoryEloquent;

class RepositoryServiceProvider extends ServiceProvider
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
        $this->app->bind(SendingRepository::class, SendingRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\KekRepository::class, \App\Repositories\KekRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\MemberRepository::class, \App\Repositories\MemberRepositoryEloquent::class);
        //:end-bindings:
    }
}
