<?php

namespace App\Services\SlackClients;

use React\EventLoop\Factory;

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
     * Client constructor.
     */
    public function __construct($token)
    {
        $this->token = $token;
        $this->loop = Factory::create();
        $this->loop->run();
    }
}