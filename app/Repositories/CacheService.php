<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Cache;

class CacheService implements CacheServiceInterface
{
    const TWEET_KEY = "TWEET-";

    public function get(string $key) {
       if (Cache::has($key)) {
            return Cache::get($key);
        }
        return null;
    }

    public function put(string $key, $value, $duration = null) {
        if (empty($duration)) $duration = 60*60*2;
        Cache::put($key, $value, $duration);
    }
}