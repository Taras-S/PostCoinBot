<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ReactionAdded extends Event
{
    use SerializesModels;

    /**
     * Who added a reaction?
     *
     * @var
     */
    public $from_slack_id;

    /**
     * Who recieved a reaction?
     *
     * @var
     */
    public $to_slack_id;

    /**
     * For what type of item reaction was added?
     *
     * @var
     */
    public $where;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->from_slack_id = $data['user'];
        $this->to_slack_id = $data['item_user'];
        $this->where = $data['item']['type'];
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
