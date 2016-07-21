<?php

namespace App\Services\Bot;

use App\Repositories\SendingRepository;
use App\Http\Requests\SlackCommandRequest;
use Illuminate\Support\Facades\Response;
use App\Entities\Member;
use App\Entities\Sending;
use Mockery\Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

// TODO: Remove all messanger-specific things to request interface

/**
 * Class that store slash commands
 */
class Commands
{
    /**
     * @var SendingRepository
     */
    private $sendings;

    /**
     * SlashCommands constructor.
     *
     * @param SendingRepository $sendings
     */
    public function __construct(SendingRepository $sendings, SlackCommandRequest $request)
    {
        $this->sendings = $sendings;
        $this->request  = $request;
    }

    /**
     * Returns a list of all available slash commands.
     * Can be useful in /help command
     *
     * @return mixed
     */
    public function getAvailableCommands()
    {
        return [];
    }

    /**
     * Sets member wallet
     *
     * @param $slack_id
     * @param $slack_name
     * @param $wallet
     * @return mixed
     */
    public function setWallet()
    {
        $member = Member::firstOrNew(['slack_id' => $this->request->user_id]);
        $member->username = $this->request->user_name;
        $member->wallet = $this->request->getInput();
        $member->save();

        return ['wallet' => $member->wallet];
    }

    /**
     * Returns members last statistic
     *
     * @param $slack_id
     * @return mixed
     */
    public function getStats()
    {
        $this_week = $this->sendings->getThisWeekStatForRecipient($this->request->user_id);
        $last_week = $this->sendings->getLastWeekStatForRecipient($this->request->user_id);

        return compact('this_week', 'last_week');
    }

    /**
     * Returns TOP 10 members for the current week
     *
     * @return mixed
     */
    public function getThisWeekTop()
    {
        $from = Carbon::now()->startOfWeek();
        $to =   Carbon::now();

        $top = $this->sendings->getTopRecipients([$from, $to], 10);

        return compact('top');
    }

    /**
     * Returns TOP 10 members for the last week
     *
     * @return mixed
     */
    public function getLastWeekTop()
    {
        $from = Carbon::now()->startOfWeek()->subWeek();
        $to   = Carbon::now()->startOfWeek();

        $top = $this->sendings->getTopRecipients([$from, $to], 10);

        return compact('top');
    }
}
