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
    private $route = [
        'setWallet'      => 'setWallet',
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
        $command = $this->route[$request->getCommand()];
        $result  = app()->call([$bot, $command], [$request->getInput()]);

        return $this->response($command, $result);
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
        return Response::view($this->responsePath . $command, $payload)
                       ->header('Content-Type', $this->responseType);
    }
}
