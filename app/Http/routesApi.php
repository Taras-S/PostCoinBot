<?php

use App\Http\Controllers\Commands\SlackCommandController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| All routes defined in this file are under API middleware
|
*/

/**
 * Slack slash commands
 */
Route::post('/api/slack/command/call', 'SlackCommandController@call');
