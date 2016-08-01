<?php

namespace App\Services\SlackClients;

use Slack\RealTimeClient;
use Carbon\Carbon;

/**
* Class APIClient
 *
* @package App\Bot
*/
class APIClient extends Client
{
    /**
     * @var Carbon int
     */
    protected $lastApiCallTime = 0;

    /**
     * Possible API calls per second
     *
     * @var int
     */
    protected $rateLimit = 1;

    /**
    * Setup API settings
    */
    public function __construct($token) // TODO: pass token of current user
    {
        parent::__construct($token);
        $this->client = new RealTimeClient($this->loop);
        $this->client->setToken($this->token);
    }

    /**
     * Sleep to respect Slack Api Rate Limit
     */
    public function respectRateLimit()
    {
        if ((Carbon::now() - $this->lastApiCallTime) > 1) sleep(1 / $this->rateLimit);
        $this->lastApiCallTime = Carbon::now();
    }

}