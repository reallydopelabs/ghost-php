<?php

namespace ReallyDope\Ghost\Exceptions;

use Exception;
use JsonException;

class UnserializableResponseException extends Exception
{
    /**
     * Create a new Unserializable Response exception.
     */
    public function __construct(JsonException $exception)
    {
        parent::__construct($exception->getMessage(), 0, $exception);
    }
}
