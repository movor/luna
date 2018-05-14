<?php

namespace App\Exceptions\ApiExceptions;

class ApiBadRequestException extends ApiBaseException
{
    public function __construct($message = '', $data = [], $code = 400, \Exception $previous = null)
    {
        parent::__construct($message, $data, $code, $previous);
    }
}