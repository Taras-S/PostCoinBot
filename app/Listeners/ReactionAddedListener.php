<?php

namespace App\Listeners;

use App\Events\ReactionAdded;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Sending;

class ReactionAddedListener
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
     * @param  ReactionAdded  $event
     * @return void
     */
    public function handle(ReactionAdded $reaction)
    {
        if ($reaction->from_slack_id == $reaction->to_slack_id) return;

        $sending = new Sending([
                'amount' => config('bot.amount'),
                'from_slack_id' => $reaction->from_slack_id,
                'to_slack_id' => $reaction->to_slack_id,
                'where' => $reaction->where,
                'type' => 'reaction',
                'done' => false
            ]
        );

        $sending->save();
    }
}
