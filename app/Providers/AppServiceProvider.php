<?php

namespace App\Providers;

use App\Client\TwitterClient;
use App\Client\TwitterClientInterface;
use App\Repositories\TweetRepository;
use App\Repositories\TweetRepositoryInterface;
use Illuminate\Support\ServiceProvider;
use App\Repositories\CacheService;
use App\Repositories\CacheServiceInterface;

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
        $this->app->bind(CacheServiceInterface::class, CacheService::class);
        $this->app->bind(TweetRepositoryInterface::class, TweetRepository::class);
    }
}
