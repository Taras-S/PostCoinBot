<?php

namespace App\Bot;

use Slack\RealTimeClient;

/**
* Class APIClient
* @package App\Bot
*/
class APIClient extends Client
{
    /**
    * Setup API settings
    */
    public function __construct($token)
    {
        parent::__construct($token);
        $this->client = new RealTimeClient($this->loop);
        $this->client->setToken($this->token);
    }
}