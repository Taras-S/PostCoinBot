<?php

namespace App\Http\Controllers\Bot\Commands;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Response;
use App\Http\Requests;
use App\Services\Bot\Commands;

/**
 * Class that handle bot commands
 *
 * @package App\Http\Controllers
 */
abstract class CommandController extends Controller
{
    /**
     * Command name to bot method name mapping
     *
     * @var Collection
     */
    protected $routes = [
    //  '/command'  => 'MethodName'
        'setwallet' => 'setWallet',
        'stat'      => 'getStats',
        'thisweek'  => 'getThisWeekTop',
        'lastweek'  => 'getLastWeekTop',
        'help'      => 'getAvailableCommands',
    ];

    /**
     * Default method, that will be executed if command not found
     *
     * @var string
     */
    protected $defaultMethod = 'getAvailableCommands';

    /**
     * CommandController constructor.
     */
    public function __construct()
    {
        $this->routes = collect($this->routes);
    }

    /**
     * Returns bot class method name by command name that it handle
     *
     * @param $command
     * @return string Method name
     */
    protected function getMethodByCommand($command)
    {
        return $this->routes->get($command, $this->defaultMethod);
    }
}
