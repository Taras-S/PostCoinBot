<?php

namespace App\Services\Bot;

use App\Repositories\SendingRepository;
use App\Http\Requests\SlackCommandRequest;
use Illuminate\Support\Facades\Response;
use App\Member;
use App\Sending;
use Mockery\Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
    public function __construct(SendingRepository $sendings)
    {
        $this->sendings = $sendings;
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
    public function setWallet($slack_id, $slack_name, $wallet)
    {
        $member = Member::firstOrNew(['slack_id' => $slack_id]);
        $member->username   = $slack_name;
        $member->wallet = $wallet;
        $member->save();

        return compact('wallet');
    }

    /**
     * Returns members last statistic
     *
     * @param $slack_id
     * @return mixed
     */
    public function getStats($slackId)
    {
        $thisWeek = $this->sendings->getThisWeekStatForRecipient($slackId);
        $last_week = $this->sendings->getLastWeekStatForRecipient($slackId);

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
