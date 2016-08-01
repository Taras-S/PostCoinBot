<?php

namespace App\Services\SlackClients;

use React\EventLoop\Factory;
use Carbon\Carbon;

/**
 * Class Client
 *
 * @package App\Bot
 */
abstract class Client
{
    /**
     * React loop
     *
     * @var \React\EventLoop\ExtEventLoop|\React\EventLoop\LibEventLoop|\React\EventLoop\LibEvLoop|\React\EventLoop\StreamSelectLoop
     */
    public $loop;

    /**
     * Slack client
     *
     * @var \Slack\ApiClient;
     */
    public $client;

    /**
     * Slack token
     *
     * @var string
     */
    public $token;

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
     * Client constructor.
     */
    public function __construct($token)
    {
        $this->token = $token;
        $this->loop = Factory::create();
        $this->loop->run();
    }

    /**
     * Shortcut: do not need to type ->client-> every time you need to access client
     *
     * @param $method
     * @param $arguments
     */
    public function __call($method, $arguments)
    {
        if (method_exists($this, $method)) {
            $this->$method(...$arguments);
        } else {
            $this->respectRateLimit();
            $this->client->$method(...$arguments);
        }
    }

    /**
     * Sleep to respect Slack Api Rate Limit
     */
    protected function respectRateLimit()
    {
        if ((Carbon::now() - $this->lastApiCallTime) > 1) sleep(1 / $this->rateLimit);
        $this->lastApiCallTime = Carbon::now();
    }
}