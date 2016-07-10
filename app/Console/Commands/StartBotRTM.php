<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;
use Slack\RealTimeClient;
use App\Sending;

class StartBotRTM extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Starts Slack RTM session';

    /**
     * React loop
     *
     * @var LoopInterface
     */
    protected $loop;

    /**
     * RTM Client
     *
     * @var RealTimeClient
     */
    protected $client;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->loop = Factory::create();

        $this->client = new RealTimeClient($this->loop);
        $this->client->setToken('YOUR-TOKEN-HERE');

        $this->addEventsHandlers();

        $this->loop->run();
    }

    /**
     * Adds events handlers
     */
    protected function addEventsHandlers()
    {
        $this->client->on('reaction_added', [$this, 'addSendingFromReaction']);
    }

    /**
     * Adds sending by reaction_added RTM event
     *
     * @param $data Slack RTM Dataset
     */
    protected function getSendingFromReaction($data)
    {
        if ($data['user'] == $data['item_user']) return;

        $sending = new Sending([
                'amount' => env('SLACKBOT_DEFAULT_AMOUNT'),
                'from_slack_id' => $data['user'],
                'to_slack_id' => $data['item_user'],
                'from_name' => false,  // TODO: add name from id function
                'to_name' => false, // TODO: add name from id function
                'where' => $data['item']['type'],
                'type' => 'reaction',
                'done' => false
            ]
        );

        $sending->save();
    }
}
