<?php

namespace App\Services;
use App\Client\TwitterClientInterface;
use Exceptions\InvalidTweetUrlException;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

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
            $retweeterIds = $this->twitterClient->getRetweeterIdsByTweetId($tweetId);
            $users = $this->twitterClient->getUsersById($retweeterIds);
            return $this->countTotalFollowers($users);
        } catch (InvalidTweetUrlException $exception) {
            Log::error("The URL " . $url . " does not contain a tweet ID");
            throw new BadRequestHttpException("The URL provided is not correct.");
        }
    }

    private function countTotalFollowers(array $users): int {
        return array_reduce($users, function ($carry, $user) {
            return $carry + $user->followers_count;
        });
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