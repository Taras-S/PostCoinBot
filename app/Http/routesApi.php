<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| All routes defined in this file are under API middleware
|
*/

Route::group(['namespace' => 'Bot'], function() {

    /**
     * Slack slash commands
     */
    Route::post('/api/slack/command/call', 'Commands\SlackCommandController@call');

    /**
     * Slack Events
     */
    Route::post('/api/slack/event/fire', 'SlackSendingReactionEventListener@fire')->middleware('sendingReaction');
});