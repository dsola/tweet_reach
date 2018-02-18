<?php

namespace App\Client;

use App\Entities\TwitterUser;
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
            $this->throwException();
        }
    }

    /**
     * @param int[] $ids
     * @return TwitterUser[]
     */
    public function getUsersById(array $ids): array {
        try {
            $users = Twitter::getUsersLookup(['user_id' => implode(',', $ids)]);
            return array_map(function($user) {
                return new TwitterUser($user->followers_count);
            }, $users);
        } catch (\Exception $exception) {
            $this->throwException();
        }
    }

    private function throwException() {
        $logs = Twitter::logs();
        throw new TwitterClientErrorException(var_dump($logs));
    }
}