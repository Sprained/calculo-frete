<?php

namespace Sprained\Correios\Exceptions;

use Exception;

class TrackingException extends Exception
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}