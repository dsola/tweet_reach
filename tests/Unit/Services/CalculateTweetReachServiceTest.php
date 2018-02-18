<?php

namespace Tests\Unit\App\Services;

use App\Exceptions\TwitterClientErrorException;
use App\Entities\TwitterUser;
use App\Exceptions\BadRequestException;
use App\Exceptions\ServerErrorException;
use App\Repositories\TweetRepositoryInterface;
use App\Services\CalculateTweetReachService;
use Tests\TestCase;

class CalculateTweetReachServiceTest extends TestCase
{
    /**
     * @var TweetRepositoryInterface
     */
    private $repository;

    protected function setUp()
    {
        parent::setUp();
        $this->repository = \Mockery::mock(TweetRepositoryInterface::class);
    }

    public function test_when_url_is_invalid() {
        $url = "http://twitter.com/invalid";

        $this->expectException(BadRequestException::class);

        $service = new CalculateTweetReachService($this->repository);
        $service->execute($url);
    }

    public function test_the_tweet_id_is_properly_extracted() {
        $tweetId = "1234567";
        $url = "http://twitter.com/{$tweetId}";

        $this->repository->shouldReceive('getNumberOfFollowersByTweetId')->with($tweetId)->andReturn(5);
        $service = new CalculateTweetReachService($this->repository);
        $this->assertEquals(5, $service->execute($url));
    }

    public function test_when_an_api_error_occurs_when_the_retweeters_are_requested() {
        $url = "http://twitter.com/1234567";

        $this->repository->shouldReceive('getNumberOfFollowersByTweetId')->andThrow(TwitterClientErrorException::class);
        $service = new CalculateTweetReachService($this->repository);

        $this->expectException(ServerErrorException::class);

        $service->execute($url);
    }

    protected function tearDown()
    {
        unset($this->repository);
        parent::tearDown();
    }


}
