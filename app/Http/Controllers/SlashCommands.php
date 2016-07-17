<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Http\Requests;
use App\Member;
use App\Sending;
use Mockery\Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use BotCommand;

/**
 * Class that handle slack slash commands
 *
 * @package App\Http\Controllers
 */
class SlashCommands extends Controller
{
    /**
     * Determinate which command need to call and returns result
     *
     * @param Request $request
     * @return string
     */
    public function process(Request $request)
    {
        $payload = explode(' ', $request->text);
        $command = $payload[0];
        isset($payload[1]) ? $data = $payload[1] : $data = NULL;

        switch ($command) {
            case 'setwallet':
                return $this->setWallet($request->user_id, $request->user_name, $data);
                break;
            case 'stat':
                return $this->getStats($request->user_id);
                break;
            case 'thisweek':
                return $this->getThisWeekTop();
                break;
            case 'lastweek':
                return $this->getLastWeekTop();
                break;
            default:
                // no break
            case 'help':
                return $this->getAvailableCommands();
                break;
        }
    }

    /**
     * Returns a list of all available slash commands.
     * Can be useful in /help command
     *
     * @return mixed
     */
    public function getAvailableCommands()
    {
        return BotCommand::response(__FUNCTION__, []);
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

        return BotCommand::response(__FUNCTION__, compact('wallet'));
    }

    /**
     * Returns members last statistic
     *
     * @param $slack_id
     * @return mixed
     */
    public function getStats($slack_id)
    {
        $this_week = Sending::where('to_slack_id', $slack_id)
                            ->where('created_at', '>', Carbon::now()->startOfWeek())
                            ->count();

        $last_week = Sending::where('to_slack_id', $slack_id)
                            ->where('created_at', '>', Carbon::now()->startOfWeek()->subWeek())
                            ->count();

        return BotCommand::response(__FUNCTION__, compact('this_week', 'last_week'));
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

        return BotCommand::response('getTopMembers', ['members' => Sending::getTopRecipients([$from, $to], 10)]);
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

        return BotCommand::response('getTopMembers', ['members' => Sending::getTopRecipients([$from, $to], 10)]);
    }
}
