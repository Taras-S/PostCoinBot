<?php

namespace App\Exceptions;

class SenderLimitExceededException extends SendingException
{
    protected $sendToRecipient = false;
    protected $view = 'senderLimitExceededError';
}