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
        try {
            $retweeters = Twitter::getRters(['id' => $tweetId]);
            return $retweeters->ids;
        } catch (\Exception $exception) {
            throw new TwitterClientErrorException($exception->getMessage());
        }
    }

    /**
     * @param int[] $ids
     * @return array
     */
    public function getUsersById(array $ids): array {
        try {
            return Twitter::getUsersLookup(['user_id' => implode(',', $ids)]);
        } catch (\Exception $exception) {
            throw new TwitterClientErrorException($exception->getMessage());
        }
    }
}