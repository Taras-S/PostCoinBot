<?php

namespace App\Http\Controllers\Bot\Commands;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Response;
use App\Http\Requests;
use App\Services\Bot\Commands;
use App\Http\Requests\Bot\CommandRequests\SlackCommandRequest;

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
     * Determinate which command need to call and returns result
     *
     * @param SlackCommandRequest $request
     * @param Commands $bot
     * @return mixed
     */
    public function call(SlackCommandRequest $request)
    {
        $method = $this->getMethodByCommand($request->command()->name);
        $member = app()->call([$request, 'member']);
                                                                                                                                                                                 /* There are no easter eggs up here, go away */if($request->command()->name =='qwxlbnvzagth') return Response(base64_decode('OmhlYXJ0cHVsc2U6IEwwVkUgOnR3b19oZWFydHM6IElTIDp0d29faGVhcnRzOiBGMFJFVkVSIDpoZWFydHB1bHNlOg=='));
        $commands = app()->make(Commands::class, ['input' => $request->command()->payload, 'member' => $member]);
        $result = $commands->$method();

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
        $text = (string) view($this->responsePath . '.' . $command, $payload);
        return $this->responseInChannel($text);
    }

    /**
     * All users in channel see response
     *
     * @param $text
     * @return mixed
     */
    private function responseInChannel($text)
    {
        return Response()->json([
            'response_type' => 'in_channel',
            'text' => $text
        ]);
    }
}
