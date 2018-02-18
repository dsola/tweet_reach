<?php

namespace App\Services;
use Exceptions\InvalidTweetUrlException;
use Thujohn\Twitter\Facades\Twitter;

class CalculateTweetReachService
{
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