<?php

namespace App\Client;

interface TwitterClientInterface
{
    /**
     * @param int $tweetId
     * @return int[]
     */
    public function getRetweeterIdsByTweetId(int $tweetId): array;

    /**
     * @param int[] $ids
     * @return array
     */
    public function getUsersById(array $ids): array;
}