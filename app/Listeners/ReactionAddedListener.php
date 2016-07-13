<?php

namespace App\Listeners;

use App\Events\ReactionAdded;
use App\Member;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Sending;
use Illuminate\Support\Facades\Response;
use Slack\Message\Message;

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
    public function handle(ReactionAdded $reaction, SlackChat $chat, SlackAPI $slack)
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

        $this->notifyIfWalletIsNotSet($sending->to_slack_id, $slack, $chat);
    }


    protected function notifyIfWalletIsNotSet($slack_id, $slack, $chat)
    {
        if (Member::where('slack_id', $slack_id)->first()->wallet == NULL) {
            $message = $chat->client->getMessageBuilder()->setText(
                Response::view('bot.response.chat.walletNotSet')
            )->setChannel($slack->getDMByUserId($slack_id));

            $chat->client->sendMessage($message);
        }
    }
}
