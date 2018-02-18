<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::redirect('/', 'tweet/reach')->name('tweet.reach');

Route::get('tweet/reach', 'TweetController@displayTweetReach')->name('tweet.reach');
Route::post('tweet/reach', 'TweetController@processTweet')->name('tweet.reach.process');
