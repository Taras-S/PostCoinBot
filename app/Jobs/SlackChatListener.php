<?php

namespace App\Jobs;

use App\Events\ReactionAdded;
use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use React\EventLoop\Factory;
use Slack\RealTimeClient;
use App\Events\Event;

class SlackChatListener extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var RealTimeClient
     */
    protected $chat;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(SlackChat $chat)
    {
        $this->chat = $chat->client;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->chat->on('reaction_added', [$this, 'reactionAdded']);
    }

    /**
     * Fires ReactionAdded event
     *
     * @param $data Slack data
     * @return void
     */
    public function reactionAdded($data)
    {
        $this->checkDBConnection();
        Event::fire(new ReactionAdded($data));
    }

    /**
     * Reconnects to DB, if connection is lost (useful and daemons)
     */
    protected function checkDBConnection()
    {
        try {
            DB::connection()->getDatabaseName();
        }
        catch (PDOException $e) {
            DB::reconnect();
        }
    }
}
