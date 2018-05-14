<?php

namespace App\Exceptions\ApiExceptions;

class ApiUnauthorizedException extends ApiBaseException
{
    public function __construct($message = '', $data = [], $code = 401, \Exception $previous = null)
    {
        parent::__construct($message, $data, $code, $previous);
    }
}