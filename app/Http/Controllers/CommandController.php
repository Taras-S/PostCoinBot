<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Http\Requests;
use App\Services\Bot\Commands;
use App\Http\Requests\CommandRequestInterface;
/**
 * Class that handle bot commands
 *
 * @package App\Http\Controllers
 */
abstract class CommandController extends Controller
{
    /**
     * Command name to bot method name relations table
     *
     * @var array
     */
    private $routes = [
        'setwallet'      => 'setWallet',
        'stat'           => 'getStats',
        'thisweek'       => 'getThisWeekTop',
        'lastweek'       => 'getLastWeekTop',
        'help'           => 'getAvailableCommands',

        '' /* Default */ => 'getAvailableCommands'
    ];

    /**
     * Returns bot class method name by command name that it handle
     *
     * @param $command
     * @return string Method name
     */
    protected function getMethodByCommand($command)
    {
        if (!$this->isCommandExists($command)) $command = '';
        return $this->routes[$command];
    }

    /**
     * True if command name exists
     *
     * @param $command
     * @return bool
     */
    protected function isCommandExists($command)
    {
        return array_key_exists($command, $this->routes);
    }
}
