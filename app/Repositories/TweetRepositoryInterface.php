<?php

namespace App\Repositories;

interface TweetRepositoryInterface
{
    public function getNumberOfFollowersByTweetId(int $tweetId): int;

    /**
     * @param int $tweetId
     * @return string
     */
    public function generateKey(int $tweetId): string;

    /**
     * @param int $tweetId
     * @return int
     */
    public function calculateNumberOfFollowers(int $tweetId): int;
}