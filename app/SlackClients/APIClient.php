<?php

namespace App\SlackClients;

use Slack\RealTimeClient;

/**
* Class APIClient
* @package App\SlackClients
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

    public function createMessage
}