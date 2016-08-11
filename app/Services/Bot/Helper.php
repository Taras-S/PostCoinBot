<?php

namespace App\Services\Bot;

/**
 * Bot helper functions
 *
 * @package App\Services\Bot
 */
class Helper
{
    /**
     * Where bot response views is stored
     *
     * @var string
     */
    protected $basePath = 'bot.responses';

    /**
     * Returns chat reponse
     *
     * @param $template
     * @param $payload
     * @return string
     */
    public function chatResponse($template, $payload = [])
    {
        return $this->getResponse('chat', $template, $payload);
    }

    /**
     * Returns command response
     *
     * @param $template
     * @param $payload
     * @return string
     */
    public function commandResponse($template, $payload = [])
    {
        return $this->getResponse('commands', $template, $payload);
    }

    /**
     * Returns response
     *
     * @param $path
     * @param $template
     * @param $payload
     * @return string
     */
    protected function getResponse($path, $template, $payload)
    {
        return (string) view($this->basePath . '.' . $path . '.' . $template, $payload);
    }
}