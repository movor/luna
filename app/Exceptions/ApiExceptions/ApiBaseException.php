<?php

namespace App\Exceptions\ApiExceptions;

use Symfony\Component\HttpFoundation\Response;

abstract class ApiBaseException extends \Exception
{
    protected $data = [];

    public function __construct($message = null, $data = [], $code = 0, \Exception $previous = null)
    {
        // Set custom data
        $this->data = $data;

        // Set message to be standard HTTP code message if custom message not provided
        if (is_null($message)) $message = Response::$statusTexts[$code];

        parent::__construct($message, $code, $previous);
    }

    public function getData()
    {
        return $this->data;
    }
}