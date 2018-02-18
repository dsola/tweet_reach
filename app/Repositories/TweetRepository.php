<?php

namespace App\Repositories;

use App\Client\TwitterClientInterface;
use App\Entities\TwitterUser;

class TweetRepository implements TweetRepositoryInterface
{
    /**
     * @var TwitterClientInterface
     */
    private $twitterClient;
    /**
     * @var CacheServiceInterface
     */
    private $cache;

    public function __construct(TwitterClientInterface $twitterClient, CacheServiceInterface $cacheService)
    {
        $this->twitterClient = $twitterClient;
        $this->cache = $cacheService;
    }

    public function getNumberOfFollowersByTweetId(int $tweetId): int {
        $cacheKey = $this->generateKey($tweetId);
        $numberOfFollowers = $this->cache->get($cacheKey);
        if (empty($numberOfFollowers)) {
            $numberOfFollowers = $this->calculateNumberOfFollowers($tweetId);
            $this->cache->put($cacheKey, $numberOfFollowers);
        }
        return $numberOfFollowers;
    }

    /**
     * @param TwitterUser[] $users
     * @return int
     */
    private function countTotalFollowers(array $users): int {
        $total = array_reduce($users, function ($carry, TwitterUser $user) {
            return $carry + $user->getNumberOfFollowers();
        });
        if (is_null($total)) return 0;
        return $total;
    }

    /**
     * @param int $tweetId
     * @return string
     */
    public function generateKey(int $tweetId): string
    {
        return CacheService::TWEET_KEY . $tweetId;
    }

    /**
     * @param int $tweetId
     * @return int
     */
    public function calculateNumberOfFollowers(int $tweetId): int
    {
        $retweeterIds = $this->twitterClient->getRetweeterIdsByTweetId($tweetId);
        $users = $this->twitterClient->getUsersById($retweeterIds);
        return $this->countTotalFollowers($users);
    }
}