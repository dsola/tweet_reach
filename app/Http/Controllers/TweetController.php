<?php

namespace App\Http\Controllers;

use App\Services\CalculateTweetReachService;
use App\Validators\TweetUrlValidator;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class TweetController extends BaseController
{
    public function displayTweetReach() {
        return view("tweet_reach_form");
    }

    public function processTweet(Request $request, TweetUrlValidator $validator, CalculateTweetReachService $service) {
        $validator->validate($request);
        $reach = $service->execute($request->get('tweet'));
        return view("tweet_reach_result", ['reach' => $reach]);
    }
}