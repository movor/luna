<?php

namespace App\Exceptions\ApiExceptions;

use Symfony\Component\HttpFoundation\Response;

abstract class ApiBaseException extends \Exception
{
    public function __construct($message = '', $code = 0, \Exception $previous = null)
    {
        // Set message to be standard HTTP code message if custom message not provided
        if ($message == '') $message = Response::$statusTexts[$code];

        parent::__construct($message, $code, $previous);
    }
}