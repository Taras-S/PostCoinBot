<?php

namespace App\Http\Controllers\Bot;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Jobs\UpdateMemberNames;
use App\Repositories\MemberRepository;
use App\Repositories\SendingRepository;
use App\Exceptions\SendingException;

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
     */
    public function __construct(MemberRepository $member, SendingRepository $sending, SlackAPI $api)
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
        return $this->addSending($request->input('event.user'), $request->input('event.item_user'));
    }

    /**
     * Add sending and response about success/fail to members
     *
     * @param $senderId
     * @param $recipientId
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    protected function addSending($senderId, $recipientId)
    {
        $sender = $this->member->getFromMessenger('slack', $senderId);
        $recipient = $this->member->getFromMessenger('slack', $recipientId);
        $this->dispatch((new UpdateMemberNames([$sender, $recipient]))->delay(5));

        try {
            $this->sending->add($sender, $recipient);
            $this->respondToMember($recipient->messenger_id, 'YouReceivedSending');
        } catch (SendingException $error) {
            $this->respondToMember($recipient->messenger_id, $error->getMessage());
        }

        return Response('Sending handled');
    }

    /**
     *
     * Send response to member
     * @param string $member Slack user ID that we want to notify
     * @param string $text
     */
    protected function respondToMember($member, $text)
    {
        return $this->api->execute('chat.postMessage', [
            'channel' => $member,
            'text'    => $text
        ]);
    }
}