<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return Redirect('https://github.com/Lisennk/PostCoinBot#moneybag-postcoin-bot-moneybag');
});

Route::get('auth/slack', 'Auth\AuthController@redirectToProvider')->name('auth.slack');
Route::get('auth/slack/callback', 'Auth\AuthController@handleProviderCallback');

Route::auth();

Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
