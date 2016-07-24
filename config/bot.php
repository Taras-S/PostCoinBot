<?php

/**
 * PostCoin bot settings
 */
return [
    /**
     * Default amount of sending
     */
    'amount' => 1,
    'slack' => [
        'slashCommandsToken' => env('SLACK_SLASH_COMMANDS_TOKEN')
    ]
];
