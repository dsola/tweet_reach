<?php

namespace Repositories;

use App\Client\TwitterClientInterface;
use App\Entities\TwitterUser;
use App\Exceptions\TwitterClientErrorException;
use App\Repositories\CacheServiceInterface;
use App\Repositories\TweetRepository;
use Tests\TestCase;

class TweetRepositoryTest extends TestCase
{
    const TWEET_ID = 1234567;

    /**
     * @var TwitterClientInterface
     */
    private $client;
    /**
     * @var CacheServiceInterface
     */
    private $cache;

    protected function setUp()
    {
        parent::setUp();
        $this->client = \Mockery::mock(TwitterClientInterface::class);
        $this->cache = \Mockery::mock(CacheServiceInterface::class);
    }

    public function test_when_are_no_retweeters() {
        $this->cache->shouldReceive('get')->andReturn(null);
        $this->cache->shouldReceive('put')->once();

        $this->client->shouldReceive('getRetweeterIdsByTweetId')->andReturn([]);
        $this->client->shouldReceive('getUsersById')->andReturn([]);
        $repository = new TweetRepository($this->client, $this->cache);
        $this->assertEquals(0, $repository->getNumberOfFollowersByTweetId(self::TWEET_ID));
    }

    public function test_when_are_retweeters_but_no_info()
    {
        $retweeters = [11, 22];
        $this->cache->shouldReceive('get')->andReturn(null);
        $this->cache->shouldReceive('put')->once();

        $this->client->shouldReceive('getRetweeterIdsByTweetId')->andReturn($retweeters);
        $this->client->shouldReceive('getUsersById')->with($retweeters)->andReturn([]);
        $repository = new TweetRepository($this->client, $this->cache);
        $this->assertEquals(0, $repository->getNumberOfFollowersByTweetId(self::TWEET_ID));
    }

    public function test_when_an_api_error_occurs_when_the_retweeters_are_requested() {
        $this->cache->shouldReceive('get')->andReturn(null);
        $this->client->shouldReceive('getRetweeterIdsByTweetId')->andThrow(TwitterClientErrorException::class);
        $repository = new TweetRepository($this->client, $this->cache);

        $this->expectException(TwitterClientErrorException::class);

        $repository->getNumberOfFollowersByTweetId(self::TWEET_ID);
    }

    public function test_when_an_api_error_occurs_when_the_users_are_requested() {
        $this->cache->shouldReceive('get')->andReturn(null);
        $this->client->shouldReceive('getRetweeterIdsByTweetId')->andReturn([]);
        $this->client->shouldReceive('getUsersById')->andThrow(TwitterClientErrorException::class);

        $repository = new TweetRepository($this->client, $this->cache);

        $this->expectException(TwitterClientErrorException::class);

        $repository->getNumberOfFollowersByTweetId(self::TWEET_ID);
    }

    public function test_when_only_one_user_has_received() {
        $numberOfFollowers = 3;

        $this->cache->shouldReceive('get')->andReturn(null);
        $this->cache->shouldReceive('put')->once();

        $this->client->shouldReceive('getRetweeterIdsByTweetId')->andReturn([]);

        $this->client->shouldReceive('getUsersById')->andReturn([
            0 => new TwitterUser($numberOfFollowers)
        ]);

        $repository = new TweetRepository($this->client, $this->cache);
        $this->assertEquals($numberOfFollowers, $repository->getNumberOfFollowersByTweetId(self::TWEET_ID));
    }

    public function test_when_the_response_contains_multiple_users() {
        $numberOfFollowers = 3;
        $numberOfFollowers2 = 5;

        $this->cache->shouldReceive('get')->andReturn(null);
        $this->cache->shouldReceive('put')->once();

        $this->client->shouldReceive('getRetweeterIdsByTweetId')->andReturn([]);

        $this->client->shouldReceive('getUsersById')->andReturn([
            0 => new TwitterUser($numberOfFollowers),
            1 => new TwitterUser($numberOfFollowers2)
        ]);

        $repository = new TweetRepository($this->client, $this->cache);
        $this->assertEquals($numberOfFollowers + $numberOfFollowers2, $repository->getNumberOfFollowersByTweetId(self::TWEET_ID));
    }

    public function test_when_the_cache_contains_the_value() {
        $numberOfFollowers = 3;

        $this->cache->shouldReceive('get')->andReturn($numberOfFollowers);

        $this->client->shouldReceive('getRetweeterIdsByTweetId')->times(0);
        $this->client->shouldReceive('getUsersById')->times(0);

        $repository = new TweetRepository($this->client, $this->cache);
        $this->assertEquals($numberOfFollowers, $repository->getNumberOfFollowersByTweetId(self::TWEET_ID));

    }

}
