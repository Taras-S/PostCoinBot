<?php

namespace App\Listeners;

use App\Events\SendingAdded;
use App\Entities\Member;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Entities\Sending;
use Illuminate\Support\Facades\Response;
use Slack\Message\Message;

class SendingAddedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param SendingAdded $sending
     * @return void
     */
    public function handle(SendingAdded $sending)
    {
        // TODO: actually send postcoin when sended added
    }
}
