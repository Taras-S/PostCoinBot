<?php

namespace App\Exceptions;

use App\Facades\BotHelper;
use Illuminate\Support\Facades\Response;
use Exception;

class SendingException extends Exception
{
    /**s
     * View to render error message
     *
     * @var string
     */
    protected $view = 'sendingError';

    /**
     * Notify about this error will be sended to recipient (true) or sender (false)?
     *
     * @var bool
     */
    protected $sendToRecipient = false;

    /**
     * SendingException constructor.
     * @param null $message
     * @param int $code
     * @param Exception $previous
     */
    public function __construct($message = null, $code = 0, Exception $previous = null)
    {
        if (empty($message)) $message = BotHelper::chatResponse($this->view);
        parent::__construct($message, $code, $previous);
    }

    /**
     * Determinates, who should receive notification about error and returns his
     *
     * @param $sender
     * @param $recipient
     * @return mixed
     */
    public function sendToSenderOrRecipient($sender, $recipient)
    {
        return $this->sendToRecipient ? $recipient : $sender;
    }
}