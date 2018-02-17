<?php

namespace App\Validators;

use Illuminate\Http\Request;

class TweetUrlValidator
{
    public function validate(Request $request) {
        $request->validate([
            //TODO: Check if the url comes from twitter
           'tweet' => 'required|url'
        ]);
    }
}