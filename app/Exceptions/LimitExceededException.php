<?php

namespace App\Exceptions;

class TeamLimitExceededException extends SendingException
{
    protected $view = 'teamLimitExceededError';
}