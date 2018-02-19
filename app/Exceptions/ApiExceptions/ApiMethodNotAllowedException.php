<?php

namespace App\Exceptions\ApiExceptions;

class ApiMethodNotAllowedException extends ApiBaseException
{
    public function __construct($message = "", $code = 405, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}