<?php

namespace App\Client;

use App\Entities\TwitterUser;

interface TwitterClientInterface
{
    /**
     * @param int $tweetId
     * @return int[]
     */
    public function getRetweeterIdsByTweetId(int $tweetId): array;

    /**
     * @param int[] $ids
     * @return TwitterUser[]
     */
    public function getUsersById(array $ids): array;
}