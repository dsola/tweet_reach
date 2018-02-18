<?php

namespace App\Repositories;

interface CacheServiceInterface
{
    public function get(string $key);

    public function put(string $key, $value, $duration = null);
}