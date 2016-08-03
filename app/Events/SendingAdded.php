<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Entities\Sending;

class SendingAdded extends Event
{
    use SerializesModels;

    public $sending;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Sending $sending)
    {
        $this->sending= $sending;
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
