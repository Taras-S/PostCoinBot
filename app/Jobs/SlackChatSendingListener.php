<?php

namespace App\Jobs;

use App\Exceptions\SendingException;
use App\Repositories\MemberRepository;
use App\Repositories\SendingRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use App\Services\SlackClients\APIClient;
use App\Services\SlackClients\RTMClient;
use Slack\RealTimeClient;
use PDOException;

class SlackChatListener extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var SendingRepository
     */
    protected $sending;

    /**
     * @var MemberRepository
     */
    protected $member;

    /**
     * @var \Slack\ApiClient
     */
    protected $api;

   /**
     * @var RealTimeClient
     */
    protected $chat;

    /**
     * Create a new job instance.
     *
     * @param RTMClient $chat
     * @param APIClient $api
     * @param MemberRepository $member
     * @param SendingRepository $sending
     */
    public function __construct(RTMClient $chat, APIClient $api, MemberRepository $member, SendingRepository $sending)
    {
        $this->chat = $chat->client;
        $this->api = $api->client;
        $this->member = $member;
        $this->sending = $sending;
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
     * Handle reaction added slack chat event
     *
     * @param array $data
     */
    protected function reactionAdded(array $data)
    {
        if (!$this->reactionIsCorrect($data['reaction'])) return;
        $this->openDBConnectionIfLost();

        $sender = $this->member->getFromMessenger('slack', $data['user']);
        $recipient = $this->member->getFromMessenger('slack', $data['item_user']);

        try {
            $this->sending->add($sender, $recipient);
        } catch (SendingException $error) {
            $this->respondToUser($data['item_user'], $error->getMessage());
        }
    }

    /**
     * Send response to user
     *
     * @param string $slackId User ID
     * @param View $view
     */
    protected function respondToUser($slackId, $text)
    {
        $dm = $this->api->getDMByUserId($slackId);
        $message =$this->chat->getMessageBuilder()->setText($text)->setChannel($dm);

        $this->chat->sendMessage($message);
    }

    /**
     * True if its reaction that we use to make sendings
     *
     * @param $reaction
     * @return bool
     */
    protected function reactionIsCorrect($reaction)
    {
        return $reaction == config('bot.slack.sendingReaction');
    }

    /**
     * Reconnects to DB, if connection is lost (useful and daemons)
     */
    protected function openDBConnectionIfLost()
    {
        try {
            DB::connection()->getDatabaseName();
        } catch (PDOException $e) {
            DB::reconnect();
        }
    }
}