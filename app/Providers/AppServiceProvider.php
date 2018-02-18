<?php

namespace App\Providers;

use App\Client\TwitterClient;
use App\Client\TwitterClientInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(TwitterClientInterface::class, TwitterClient::class);
    }
}
