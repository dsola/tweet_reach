<?php

namespace Tests\Unit\App\Services;

use App\Exceptions\TwitterClientErrorException;
use App\Client\TwitterClientInterface;
use App\Entities\TwitterUser;
use App\Exceptions\BadRequestException;
use App\Exceptions\ServerErrorException;
use App\Services\CalculateTweetReachService;
use Tests\TestCase;

class CalculateTweetReachServiceTest extends TestCase
{
    /**
     * @var TwitterClientInterface
     */
    private $client;

    protected function setUp()
    {
        parent::setUp();
        $this->client = \Mockery::mock(TwitterClientInterface::class);
    }

    public function test_when_url_is_invalid() {
        $url = "http://twitter.com/invalid";

        $this->expectException(BadRequestException::class);

        $service = new CalculateTweetReachService($this->client);
        $service->execute($url);
    }

    public function test_when_are_no_retweeters() {
        $url = "http://twitter.com/1234567";

        $this->client->shouldReceive('getRetweeterIdsByTweetId')->andReturn([]);
        $this->client->shouldReceive('getUsersById')->andReturn([]);
        $service = new CalculateTweetReachService($this->client);
        $this->assertEquals(0, $service->execute($url));
    }

    public function test_when_are_retweeters_but_no_info() {
        $url = "http://twitter.com/1234567";
        $retweeters = [11, 22];

        $this->client->shouldReceive('getRetweeterIdsByTweetId')->andReturn($retweeters);
        $this->client->shouldReceive('getUsersById')->with($retweeters)->andReturn([]);
        $service = new CalculateTweetReachService($this->client);
        $this->assertEquals(0, $service->execute($url));
    }

    public function test_the_tweet_id_is_properly_extracted() {
        $tweetId = "1234567";
        $url = "http://twitter.com/{$tweetId}";

        $this->client->shouldReceive('getRetweeterIdsByTweetId')->with($tweetId)->andReturn([]);
        $this->client->shouldReceive('getUsersById')->andReturn([]);
        $service = new CalculateTweetReachService($this->client);
        $service->execute($url);
    }

    public function test_when_an_api_error_occurs_when_the_retweeters_are_requested() {
        $url = "http://twitter.com/1234567";

        $this->client->shouldReceive('getRetweeterIdsByTweetId')->andThrow(TwitterClientErrorException::class);
        $service = new CalculateTweetReachService($this->client);

        $this->expectException(ServerErrorException::class);

        $service->execute($url);
    }

    public function test_when_an_api_error_occurs_when_the_users_are_requested() {
        $url = "http://twitter.com/1234567";

        $this->client->shouldReceive('getRetweeterIdsByTweetId')->andReturn([]);
        $this->client->shouldReceive('getUsersById')->andThrow(TwitterClientErrorException::class);

        $service = new CalculateTweetReachService($this->client);

        $this->expectException(ServerErrorException::class);

        $service->execute($url);
    }

    public function test_when_only_one_user_has_received() {
        $url = "http://twitter.com/1234567";
        $numberOfFollowers = 3;

        $this->client->shouldReceive('getRetweeterIdsByTweetId')->andReturn([]);

        $this->client->shouldReceive('getUsersById')->andReturn([
            0 => new TwitterUser($numberOfFollowers)
        ]);

        $service = new CalculateTweetReachService($this->client);

        $this->assertEquals($numberOfFollowers, $service->execute($url));
    }

    public function test_when_the_response_contains_multiple_users() {
        $url = "http://twitter.com/1234567";
        $numberOfFollowers = 3;
        $numberOfFollowers2 = 5;

        $this->client->shouldReceive('getRetweeterIdsByTweetId')->andReturn([]);

        $this->client->shouldReceive('getUsersById')->andReturn([
            0 => new TwitterUser($numberOfFollowers),
            1 => new TwitterUser($numberOfFollowers2)
        ]);

        $service = new CalculateTweetReachService($this->client);

        $this->assertEquals($numberOfFollowers + $numberOfFollowers2, $service->execute($url));
    }

    protected function tearDown()
    {
        unset($this->client);
        parent::tearDown();
    }


}
