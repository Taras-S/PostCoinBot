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
    return view('welcome');
});

/**
 * Slack slash commands
 */
Route::post('/api/command/process',   'SlashCommands@process');

Route::post('/api/command/wallet',   'SlashCommands@setWallet');
Route::post('/api/command/stats',    'SlashCommands@getStats');
Route::post('/api/command/thisweek', 'SlashCommands@getThisWeekTop');
Route::post('/api/command/lastweek', 'SlashCommands@getLastWeekTop');
Route::post('/api/command/help',     'SlashCommands@getAvailableCommands');




