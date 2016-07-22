<?php

namespace App\Http\Controllers;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Response;
use App\Http\Requests;
use App\Services\Bot\Commands;
use App\Http\Requests\SlackCommandRequest;

/**
 * Class that handle slack slash commands
 *
 * @package App\Http\Controllers
 */
class SlackCommandController extends CommandController
{
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
     * @param SlackCommandRequest $request
     * @param Commands $bot
     * @return mixed
     */
    public function call(SlackCommandRequest $request, Commands $bot)
    {
        $method = $this->getMethodByCommand($request->command()->name);
        $member = app()->call([$request, 'member']);
        $result = app()->call([$bot, $method], [$request->command()->input, $member]);

        return $this->response($method, $result);
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
