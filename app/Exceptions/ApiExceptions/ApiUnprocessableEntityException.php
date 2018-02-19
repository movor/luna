<?php

namespace App\Exceptions\ApiExceptions;

class ApiUnprocessableEntityException extends ApiBaseException
{
    public function __construct($message = "", $code = 422, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}