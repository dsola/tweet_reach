<?php

namespace App\Services;
use App\Exceptions\TwitterClientErrorException;
use App\Exceptions\BadRequestException;
use App\Exceptions\InvalidTweetUrlException;
use App\Exceptions\ServerErrorException;
use App\Repositories\TweetRepositoryInterface;
use Illuminate\Support\Facades\Log;

class CalculateTweetReachService
{
    /**
     * @var TweetRepositoryInterface
     */
    private $repository;

    public function __construct(TweetRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(string $url): int {
        try {
            $tweetId = $this->extractTweetIdFromUrl($url);
            return $this->repository->calculateNumberOfFollowers($tweetId);
        } catch (InvalidTweetUrlException $exception) {
            Log::error("The URL " . $url . " does not contain a tweet ID");
            throw new BadRequestException("The URL provided is not correct.");
        } catch (TwitterClientErrorException $exception) {
            Log::error("Error on API request: " . $exception->getMessage());
            throw new ServerErrorException("Error trying to connect to the Twitter API");
        }
    }

    /**
     * @param string $url
     * @return int
     * @throws InvalidTweetUrlException
     */
    private function extractTweetIdFromUrl(string $url): int
    {
        $uri_params = explode('/', $url);
        $id = end($uri_params);
        if (!is_numeric($id)) {
            throw new InvalidTweetUrlException("The URL " . $url . " does not contain a tweet ID");
        }
        return $id;
    }
}