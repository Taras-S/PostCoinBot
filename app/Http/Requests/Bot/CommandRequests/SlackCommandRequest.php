<?php

namespace App\Http\Requests\Bot\CommandRequests;

use App\Entities\Member;
use App\Facades\BotHelper;
use App\Http\Requests\Request;
use App\Http\Requests\Bot\CommandRequests\Interfaces\CommandRequestInterface;
use App\Repositories\MemberRepository;
use App\Repositories\MemberRepositoryEloquent;

class SlackCommandRequest extends Request implements CommandRequestInterface
{
    /**
     * @var string Current command name
     */
    private $command;

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
     * Returns member that run the command
     *
     * @param MemberRepository $member
     * @return mixed
     */
    public function member(MemberRepository $member)
    {
        return $member->getFromMessenger('slack', $this->input('user_id'), [
                'messenger_name' => $this->input('user_name')
        ]);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->input('token') == config('bot.slack.slashCommandsToken');
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
        $this->exploded = collect(explode(' ', $this->input('text')));

        $this->command = new \stdClass;
        $this->command->name  = strtolower($this->exploded->get(0));
        $this->command->payload = $this->exploded->get(1, '');
    }

}
