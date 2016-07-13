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
     * @var ApiClient
     */
    protected $slack;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(SlackAPI $slack)
    {
        $this->recipients_ids = Sending::getUnnamedRecipients();
        $this->senders_ids = Sending::getUnnamedSenders();

        $this->slack = $slack->client;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->fillRecievers();
        sleep(1);
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
        return $this->slack->getUserById($slack_id)->then(function (\Slack\User $user) {
            return $user->getUsername();
        });
    }
}
