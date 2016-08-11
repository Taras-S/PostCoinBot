<?php

namespace App\Exceptions;

class LimitExceededException extends SendingException
{
    protected $sendToRecipient = false;
    protected $view = 'limitExceededError';
}