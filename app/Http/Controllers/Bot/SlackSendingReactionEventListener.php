<?php

namespace App\Http\Controllers\Bot;

use App\Http\Controllers\Controller;
use App\Http\Requests\Request;
use App\Jobs\UpdateMemberNames;
use App\Repositories\MemberRepository;
use App\Repositories\SendingRepository;
use App\Exceptions\SendingException;

/**
 * Listen reaction_added slack event to handle sendings via reactions
 *
 * @package App\Http\Controllers\Bot
 */
class SlackReactionSendingEventListener extends Controller
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
     * SlackReactionSendingEventListener constructor.
     *
     * @param MemberRepository $member
     */
    public function __construct(MemberRepository $member, SendingRepository $sending)
    {
        $this->member = $member;
        $this->sending = $sending;
    }

    public function fire(Request $request)
    {
        $sender = $this->member->getFromMessenger('slack', $request->input('event.user'));
        $recipient = $this->member->getFromMessenger('slack', $request->input('event.item_user'));
        $this->dispatch(new UpdateMemberNames([$sender, $recipient]))->delay(5);

        try {
            $this->sending->add($sender, $recipient);
            $this->respondToMember($recipient->messenger_id, 'YouReceivedSending');
        } catch (SendingException $error) {
            $this->respondToMember($recipient->messenger_id, $error->getMessage());
        }

        return response('', 200);
    }

    /**
     * Send response to member
     *
     * @param string $member User ID that we want to notify
     * @param string $text
     */
    protected function respondToMember($member, $text)
    {
        // TODO: use sync sdk or directly API to send response
    }
}