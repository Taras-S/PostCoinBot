<?php

namespace App\Exceptions;

class SenderLimitExceededException extends SendingException
{
    protected $view = 'senderLimitExceededError';
}