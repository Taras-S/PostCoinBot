<?php

namespace App\Exceptions;

class LimitExceededException extends SendingException
{
    protected $view = 'limitExceededError';
}