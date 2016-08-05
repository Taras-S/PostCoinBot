<?php

namespace App\Exceptions;

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
     * SendingException constructor.
     * @param null $message
     * @param int $code
     * @param Exception $previous
     */
    public function __construct($message = null, $code = 0, Exception $previous = null)
    {
        if (empty($message)) $message = $this->getview();
        parent::__construct($message, $code, $previous);
    }

    /**
     * Returns view to render sending error message
     *
     * @return mixed
     */
    protected function getView()
    {
        return Response::view('bot.response.chat.' . $this->view);
    }
}