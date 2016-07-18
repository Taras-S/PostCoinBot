<?php

namespace App\Bot;

use Slack\ApiClient;

/**
 * Class RTMClient
 * @package App\Bot
 */
class RTMClient extends Client
{
    /**
     * Setup RTM settings
     */
    public function __construct($token)
    {
        parent::__construct($token);
        $this->client = new ApiClient($this->loop);
        $this->client->setToken($this->token);
    }
}