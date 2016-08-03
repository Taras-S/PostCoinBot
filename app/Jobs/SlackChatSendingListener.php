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
use Slack\User;
use PDOException;
use function React\Promise\all;

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
     * @var RTMClient
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
        $this->chat = $chat;
        $this->api = $api;
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
        $this->checkDBconnection();

        $sender = $this->api->getUserById($data['user']);
        $recipient = $this->api->getUserById($data['user']);

        all([$sender, $recipient])->then(function ($users) use ($this) {

            $sender = $this->member->getFromMessenger('slack', $users[0]->getId(), ['name' => $users[0]->getUsername()]);
            $recipient = $this->member->getFromMessenger('slack', $users[1]->getId(), ['name' => $users[1]->getUsername()]);

            try {
                $this->sending->add($sender, $recipient);
                $this->respondToUser($recipient->messenger_id, 'YouReceivedSending');
            } catch (SendingException $error) {
                $this->respondToUser($recipient->messenger_id, $error->getMessage());
            }
        });
    }

    /**
     * Send response to user
     *
     * @param string $slackId User ID that we want to notify
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
    protected function checkDBconnection()
    {
        try {
            DB::connection()->getDatabaseName();
        } catch (PDOException $e) {
            DB::reconnect();
        }
    }
}