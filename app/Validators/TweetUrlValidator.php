<?php

namespace App\Validators;

use Illuminate\Http\Request;

class TweetUrlValidator
{
    public function validate(Request $request) {
        $request->validate([
           'tweet' => 'required|url|regex:/\btwitter.com\b/u'
        ]);
    }
}