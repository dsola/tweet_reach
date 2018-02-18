<?php

namespace App\Client;

use Thujohn\Twitter\Facades\Twitter;

class TwitterClient implements TwitterClientInterface
{
    /**
     * @param int $tweetId
     * @return int[]
     */
    public function getRetweeterIdsByTweetId(int $tweetId): array {
        $retweeters = Twitter::getRters(['id' => $tweetId]);
        return $retweeters->ids;
    }

    /**
     * @param int[] $ids
     * @return array
     */
    public function getUsersById(array $ids): array {
        return Twitter::getUsersLookup(['user_id' => implode(',', $ids)]);
    }
}