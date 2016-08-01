<?php

namespace App\Exceptions;

class WalletNotSetException extends SendingException
{
    protected $view = 'walletNotSetError';
}