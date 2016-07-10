<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Sending;

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
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->recipients_ids = Sending::getUnnamedRecipients();
        $this->senders_ids = Sending::getUnnamedSenders();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->handleRecievers();
        $this->handleSenders();

    }

    /**
     * Handle nameless recipients
     */
    public function handleRecievers()
    {
        foreach ($this->recipients_ids as $id) {
            // TODO: name to id via slack api call
            $name = true;
            $sending = Sending::firstOrFail(['to_id' => $id]);
            $sending->to_name = $name;
            $sending->save();

            sleep(1); // Cant call Slack API faster than 1 per second
        }
    }

    /**
     * Handle nameless senders
     */
    public function handleSenders()
    {
        foreach ($this->senders_ids as $id) {
            // TODO: name to id via slack api call
            $name = true;
            $sending = Sending::firstOrFail(['from_id' => $id]);
            $sending->from_name = $name;
            $sending->save();

            sleep(1); // Cant call Slack API faster than 1 per second
        }
    }
}
