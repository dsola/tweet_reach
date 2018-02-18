<?php

namespace App\Services;
use App\Exceptions\TwitterClientErrorException;
use App\Client\TwitterClientInterface;
use App\Exceptions\BadRequestException;
use App\Exceptions\InvalidTweetUrlException;
use App\Exceptions\ServerErrorException;
use App\Entities\TwitterUser;
use Illuminate\Support\Facades\Log;

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
            throw new BadRequestException("The URL provided is not correct.");
        } catch (TwitterClientErrorException $exception) {
            Log::error("Error on API request: " . $exception->getMessage());
            throw new ServerErrorException("Error trying to connect to the Twitter API");
        }
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