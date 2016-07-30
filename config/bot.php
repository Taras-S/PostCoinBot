<?php

/**
 * PostCoin bot settings
 */
return [
    /**
     * Default amount of sending
     */
    'amount' => 1,

    /**
     * Max sendings num per day for all teams
     */
    'limit' => 1000,

    /**
     * Max sendings num per day for senders
     */
    'senderLimit' => 10,

    /**
     * Slack related config
     */
    'slack' => [

        /**
         * Reaction that allow to send postcoins
         */
        'sendingReaction' => 'postcoin',

        /**
         * Your slash commands token to ensure that requests is valid
         */
        'slashCommandsToken' => env('SLACK_SLASH_COMMANDS_TOKEN')
    ]
];
