<?php

namespace App\Http\Controllers\Bot;

use App\Facades\BotHelper;
use App\Http\Controllers\Controller;
use \Illuminate\Support\Collection;
use Illuminate\Http\Request;
use App\Jobs\UpdateMemberNames;
use App\Repositories\MemberRepository;
use App\Repositories\SendingRepository;
use App\Exceptions\SendingException;
use Frlnc\Slack\Core\Commander as SlackApi;
use Illuminate\Support\Facades\Log;

/**
 * Listen reaction_added slack event to handle sendings via reactions
 *
 * @package App\Http\Controllers\Bot
 */
class SlackSendingReactionEventListener extends Controller
{
    /**
     * @var MemberRepository
     */
    protected $member;

    /**
     * @var SendingRepository
     */
    protected $sending;

    /**
     * @var \Frlnc\Slack\Core\Commander;
     */
    protected $api;

    /**
     * SlackReactionSendingEventListener constructor.
     *
     * @param MemberRepository $member
     * @param SlackApi $api
     */
    public function __construct(MemberRepository $member, SendingRepository $sending, SlackApi $api)
    {
        $this->member = $member;
        $this->sending = $sending;
        $this->api = $api;
    }

    /**
     * Handle URL verification or execute logic based on event
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function fire(Request $request)
    {
        return $this->addSending($request->input('event.user'), $request->input('event.item_user'), [
            'messenger_team_id' => $request->input('team_id')
        ]);
    }

    /**
     * Add sending and response about success/fail to members
     *
     * @param $senderId
     * @param $recipientId
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    protected function addSending($senderId, $recipientId, $update)
    {
        $sender = $this->member->getFromMessenger('slack', $senderId, $update);
        $recipient = $this->member->getFromMessenger('slack', $recipientId, $update);

        $this->updateNames([$recipient, $sender]);

        try {
            $this->sending->add($sender, $recipient);
            $this->respondToMember($recipient->messenger_id, BotHelper::chatResponse('youReceivedSending'));
        } catch (SendingException $error) {
            $sendTo = $error->sendToSenderOrRecipient($sender, $recipient);
            $this->respondToMember($sendTo->messenger_id, $error->getMessage());
        }

        return Response('Sending handled');
    }

    /**
     * Send response to member
     *
     * @param string $member Slack user ID that we want to notify
     * @param string $text
     * @return mixed
     */
    protected function respondToMember($member, $text)
    {
        return $this->api->execute('chat.postMessage', [
            'channel' => $member,
            'text'    => $text
        ]);
    }

    /**
     * Update member names
     *
     * @param array $members
     */
    protected function updateNames(array $members)
    {
        $job = (new UpdateMemberNames($this->api, ...$members))->delay(5);
        $this->dispatch($job);
    }

}