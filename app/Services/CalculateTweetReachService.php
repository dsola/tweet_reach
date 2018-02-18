<?php

namespace App\Services;
use App\Client\TwitterClientInterface;
use Exceptions\InvalidTweetUrlException;
use Thujohn\Twitter\Facades\Twitter;

class CalculateTweetReachService
{
    /**
     * @var TwitterClientInterface
     */
    private $twitterClient;

    public function __construct(TwitterClientInterface $twitterClient)
    {
        $this->twitterClient = $twitterClient;
    }

    public function execute(string $url): int {
        try {
            $tweetId = $this->extractTweetIdFromUrl($url);
            $retweeterIds = Twitter::getRters(['id' => $tweetId]);
            $users = Twitter::getUsersLookup(['user_id' => implode(',', $retweeterIds->ids)]);
            dd($users);
        } catch (InvalidTweetUrlException $exception) {

        }
    }

    public function extractTweetIdFromUrl(string $url): int
    {
        $uri_params = explode('/', $url);
        $id = end($uri_params);
        if (!is_numeric($id)) {
            throw new InvalidTweetUrlException("The URL " . $url . " does not contain a tweet ID");
        }
        return $id;
    }
}