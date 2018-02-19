<?php

namespace App\Exceptions\AppExceptions;

use Exception;

class AppErrorException extends Exception
{
    public function __construct($message = '', $code = 500, \Exception $previous = null)
    {
        if ($message == '') $message = 'Error';

        parent::__construct($message, $code, $previous);
    }
}