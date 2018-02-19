<?php

namespace App\Exceptions\ApiExceptions;

class ApiUnauthorizedException extends ApiBaseException
{
    public function __construct($message = "", $code = 401, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}