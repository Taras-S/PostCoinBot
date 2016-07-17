<?php

use Illuminate\Support\Facades\Facade;

/**
 * Class BotCommand
 */
class BotCommand extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'BotCommand';
    }
}