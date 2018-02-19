<?php

namespace App\Exceptions\ApiExceptions;

class ApiResourceNotFoundException extends ApiBaseException
{
    public function __construct($message = "", $code = 404, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}