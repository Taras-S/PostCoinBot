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
     * @var array Exploded slack request string
     */
    private $exploded;

    /**
     * Parse slack command data
     */
    public function parseRequest()
    {
        if (isset($this->exploded)) return;

        $this->exploded = explode(' ', $this->input('text'));

        $this->command = strtolower($this->exploded[0]);
        $this->input   = $this->exploded[1];
    }

    /**
     * Returns current command
     *
     * @return string
     */
    public function getCommand()
    {
        if (empty($this->command)) $this->parseRequest();

        return $this->command;
    }

    /**
     * Returns command data
     *
     * @return string
     */
    public function getInput()
    {
        if (empty($this->input)) $this->parseRequest();

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
