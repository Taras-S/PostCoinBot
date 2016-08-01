<?php

namespace App\Exceptions;

class CantSendToYourselfException extends SendingException
{
    protected $view = 'cantSendToYourselfError';
}