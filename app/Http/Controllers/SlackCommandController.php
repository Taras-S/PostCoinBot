<?php

namespace App\Http\Controllers;

use App\Repositories\SendingRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\SlackCommandRequest;
use App\Services\Bot\Commands;

/**
 * Class that handle slack slash commands
 *
 * @package App\Http\Controllers
 */
class SlackCommandController extends Controller
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
     * @var string Path to directory where responses stored
     */
    private $responsePath = 'bot.responses.commands';

    /**
     * @var string Header that will be passed with result
     */
    private $responseType = 'application/json';

    /**
     * Determinate which command need to call and returns result
     *
     * @param Request $request
     * @return string
     */
    public function call(SlackCommandRequest $request, Commands $bot)
    {
        $method = $this->getMethodByCommand($request->getCommand());
        $result  = app()->call([$bot, $method], [$request->getInput()]);

        return $this->response($method, $result);
    }

    /**
     * Returns bot class method name by command name that it handle
     *
     * @param $command
     * @return string Method name
     */
    private function getMethodByCommand($command)
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
    private function isCommandExists($command)
    {
        return array_key_exists($command, $this->routes);
    }

    /**
     * Get response based on specific view and payload, passed into this view
     *
     * @param $response
     * @param $payload
     * @return mixed
     */
    private function response($command, $payload)
    {
        return Response::view($this->responsePath . '.' . $command, $payload)
                       ->header('Content-Type', $this->responseType);
    }
}
