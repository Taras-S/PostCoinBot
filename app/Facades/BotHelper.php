<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class BotHelper
 */
class BotHelper extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'BotHelper';
    }
}