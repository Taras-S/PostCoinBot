<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class SlackCommandRequest extends Request
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
     * @return static
     */
    static function capture()
    {
        $instance = parent::capture();
        $instance->parseCommand();
        return $instance;
    }

    /**
     * Parse slack command data
     */
    public function parseCommand()
    {
        $payload = explode(' ', $this->input('text'));
        $this->command = $payload[0];
        $this->input   = $payload[1];
    }

    /**
     * Returns current command
     *
     * @return string
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * Returns command data
     *
     * @return string
     */
    public function getInput()
    {
        return $this->input;
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

}
