<?php

namespace PHPLegends\Http\Exceptions;

class HttpException extends \RuntimeException
{
    protected $statusCode;

    public function __construct($message, $statusCode = 500)
    {
        parent::__construct($message);

        $this->statusCode = $statusCode;
    }

    /**
     * Gets the value of statusCode.
     *
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }
}

