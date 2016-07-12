<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use React\EventLoop\Factory;
use App\Sending;
use Slack\ApiClient;

/**
 * Fills empty (null) names in sendings
 * @package App\Jobs
 */
class EmptySendingsNamesFiller extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Nameless recipients ids
     *
     * @var
     */
    protected $recipients_ids;

    /**
     * Nameless senders ids
     *
     * @var
     */
    protected $senders_ids;

    /**
     * React loop
     *
     * @var \React\EventLoop\ExtEventLoop|\React\EventLoop\LibEventLoop|\React\EventLoop\LibEvLoop|\React\EventLoop\StreamSelectLoop
     */
    protected $loop;

    /**
     * @var ApiClient
     */
    protected $client;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->recipients_ids = Sending::getUnnamedRecipients();
        $this->senders_ids = Sending::getUnnamedSenders();

        $this->loop = Factory::create();

        $this->client = new ApiClient($this->loop);
        $this->client->setToken('YOUR-TOKEN-HERE');

        $this->loop->run();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->fillRecievers();
        $this->fillSenders();

    }

    /**
     * Handle nameless recipients
     */
    public function fillRecievers()
    {
        foreach ($this->recipients_ids as $id) {
            // TODO: name to id via slack api call
            $name = true;
            $sending = Sending::firstOrFail(['to_id' => $id]);
            $sending->to_name = $this->getNameByID($id);
            $sending->save();

            sleep(1); // Cant call Slack API faster than 1 per second
        }
    }

    /**
     * Handle nameless senders
     */
    public function fillSenders()
    {
        foreach ($this->senders_ids as $id) {
            $sending = Sending::firstOrFail(['from_id' => $id]);
            $sending->from_name = $this->getNameByID($id);
            $sending->save();

            sleep(1); // Cant call Slack API faster than 1 per second
        }
    }

    /**
     * Returns user name by his Slack ID
     *
     * @param $slack_id
     * @return  string
     */
    protected function getNameByID($slack_id)
    {
        return $this->client->getUserById($slack_id)->then(function (\Slack\User $user) {
            return $user->getUsername();
        });
    }
}
