<?php

namespace App\Http\Requests;

use App\Entities\Member;
use App\Facades\BotHelper;
use App\Http\Requests\Request;
use App\Http\Requests\CommandRequestInterface;
use App\Repositories\MemberRepository;
use App\Repositories\MemberRepositoryEloquent;

class SlackCommandRequest extends Request implements CommandRequestInterface
{
    /**
     * @var string Current command name
     */
    private $command;

    /**
     * @var string Current command input
     */
    private $input;

    /**
     * @var array Exploded slack request string
     */
    private $exploded;

    /**
     * Returns current command
     *
     * @return string
     */
    public function command()
    {
        if (empty($this->command)) $this->parseRequest();
        return $this->command;
    }

    /**
     * Returns current member
     *
     * @return Member
     */
    public function member(MemberRepository $member)
    {
        $messengerId = BotHelper::memberId('slack', $this->input('user_id'));
        $messengerName = $this->input('user_name');

        return $member->getFromMessenger($messengerId, $messengerName);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // TODO: check if request is from slack
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    /**
     * Parse slack command data
     */
    private function parseRequest()
    {
        if (isset($this->exploded)) return;
        $this->exploded = explode(' ', $this->input('text'));

        $this->command = new \stdClass;
        $this->command->name  = strtolower($this->exploded[0]);
        $this->command->input = $this->exploded[1];
    }

}
