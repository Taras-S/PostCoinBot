<?php

use Illuminate\Support\Facades\Response;

/**
 * Functionality related to slash commands response
 */
class SlashCommandResponser
{
    private $responsePath = 'bot.responses.commands';
    private $responseType = 'application/json';

    /**
     * Get response based and specific bot view and payload, passed into this view
     *
     * @param $response
     * @param $payload
     * @return mixed
     */
    public function response($response, $payload)
    {
        return Response::view($this->responsePath . $response, $payload)->header('Content-Type', 'application/json');
    }
}