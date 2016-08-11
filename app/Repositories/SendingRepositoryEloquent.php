<?php

namespace App\Repositories;

use App\Exceptions\CantSendToYourselfException;
use App\Exceptions\LimitExceededException;
use App\Exceptions\SenderLimitExceededException;
use App\Exceptions\WalletNotSetException;
use Illuminate\Database\Eloquent\Collection;
use Mockery\CountValidator\Exception;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\SendingRepository;
use App\Entities\Sending;
use Illuminate\Support\Facades\Event;
use App\Entities\Member;
use App\Validators\SendingValidator;
use Illuminate\Support\Facades\DB;
use App\Events\SendingAdded;

/**
 * Class SendingRepositoryEloquent
 * @package namespace App\Repositories;
 * @implements SendingRepositry
 */
class SendingRepositoryEloquent extends BaseRepository implements SendingRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Sending::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * Returns current week statistics by recipient Slack ID
     *
     * @param $recipientSlackId
     * @return mixed
     */
    public function getThisWeekStatForRecipient(Member $member)
    {
        return $member->receivedSendings()->onThisWeek()->count();
    }

    /**
     * Returns previous week statistics by recipient Slack ID
     *
     * @param $recipientSlackId
     * @return mixed
     */
    public function getLastWeekStatForRecipient(Member $member)
    {
        return $member->receivedSendings()->onLastWeek()->count();
    }

    /**
     * Returns TOP recipients for period
     *
     * @param array $period
     * @param $limit Number of rows that will be returned
     * @return mixed
     */
    public function getTopRecipients(array $period, $limit)
    {
        return Sending::select(['recipient_id', 'messenger_name', DB::raw('SUM(amount) as total')])
            ->leftJoin('members', 'members.id', '=', 'sendings.recipient_id')
            ->whereBetween('sendings.created_at', $period)
            ->groupBy('recipient_id')
            ->orderBy(DB::raw('total'), 'DESC')
            ->limit($limit)
            ->get();

    }

    /**
     * Create new sending if valid
     *
     * @param Member $sender
     * @param Member $recipient
     * @return Sending
     * @throws CantSendToYourselfException
     * @throws LimitExceededException
     * @throws SenderLimitExceededException
     * @throws WalletNotSetException
     */
    public function add(Member $sender, Member $recipient)
    {
        $this->validateTransaction($sender, $recipient);

        $sending = new Sending();
        $sending->sender()->associate($sender);
        $sending->recipient()->associate($recipient);
        $sending->save();

        Event::fire(new SendingAdded($sending));

        return $sending;
    }

    /**
     * True if transaction is valid
     *
     * @param Member $sender
     * @param Member $recipient
     * @return bool
     * @throws CantSendToYourselfException
     * @throws LimitExceededException
     * @throws SenderLimitExceededException
     * @throws WalletNotSetException
     */
    protected function validateTransaction(Member $sender, Member $recipient)
    {
        if (empty($recipient->wallet)) throw new WalletNotSetException;
        if ($sender->messenger_id == $recipient->messenger_id) throw new CantSendToYourselfException;
        if ($sender->sendedSendings()->today()->count() > config('bot.senderLimit')) throw new SenderLimitExceededException;
        if (Sending::today()->count() > config('bot.limit')) throw new LimitExceededException;

        return true;
    }
}
