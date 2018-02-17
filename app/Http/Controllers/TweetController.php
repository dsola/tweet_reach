<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class TweetController extends BaseController
{
    public function displayTweetReach() {
        return view("tweet_reach_form");
    }

    public function processTweet(Request $request) {
        dd($request->get('tweet'));
    }
}